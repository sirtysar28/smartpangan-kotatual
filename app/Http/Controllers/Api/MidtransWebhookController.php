<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserNotification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handler(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received', $payload);

        // Validate signature
        $serverKey = config('services.midtrans.server_key');
        $signatureKey = $request->signature_key ?? '';
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;

        $expectedHash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($expectedHash !== $signatureKey) {
            Log::warning('Midtrans Webhook - Invalid Signature', [
                'expected' => $expectedHash,
                'received' => $signatureKey,
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find order
        $order = Order::where('order_number', $orderId)->first();
        if (!$order) {
            Log::warning('Midtrans Webhook - Order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;
        $paymentType = $request->payment_type;
        $transactionId = $request->transaction_id ?? $request->transaction_id;

        Log::info('Midtrans Webhook Processing', [
            'order_number' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
        ]);

        switch ($transactionStatus) {
            case 'capture':
                // For credit card, check fraud status
                if ($fraudStatus === 'accept') {
                    $this->markAsPaid($order, $paymentType, $transactionId, $payload);
                } elseif ($fraudStatus === 'challenge') {
                    $order->update([
                        'status' => 'pending',
                        'midtrans_response' => $payload,
                    ]);
                } elseif ($fraudStatus === 'deny') {
                    $this->markAsFailed($order, $payload);
                }
                break;

            case 'settlement':
                $this->markAsPaid($order, $paymentType, $transactionId, $payload);
                break;

            case 'pending':
                $order->update([
                    'status' => 'pending',
                    'midtrans_response' => $payload,
                ]);
                break;

            case 'cancel':
                $this->markAsFailed($order, $payload);
                break;

            case 'deny':
                $this->markAsFailed($order, $payload);
                break;

            case 'expire':
                $this->markAsFailed($order, $payload);
                break;

            case 'refund':
                $order->update([
                    'status' => 'refunded',
                    'midtrans_response' => $payload,
                ]);
                break;

            case 'partial_refund':
                $order->update([
                    'midtrans_response' => $payload,
                ]);
                break;

            case 'chargeback':
                $order->update([
                    'status' => 'refund_requested',
                    'midtrans_response' => $payload,
                ]);
                break;

            default:
                Log::warning('Midtrans Webhook - Unknown status', [
                    'transaction_status' => $transactionStatus,
                    'order_id' => $orderId,
                ]);
                break;
        }

        return response()->json(['message' => 'OK']);
    }

    private function markAsPaid(Order $order, string $paymentType, ?string $transactionId, array $payload): void
    {
        $wasAlreadyPaid = $order->status === 'paid';

        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $paymentType,
            'payment_type' => $paymentType,
            'midtrans_transaction_id' => $transactionId,
            'midtrans_response' => $payload,
        ]);

        if (!$wasAlreadyPaid) {
            $order->umkm->increment('total_sales');

            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('total_sold', $item->quantity);
            }

            // Notify UMKM owner
            UserNotification::create([
                'user_id' => $order->umkm->user_id,
                'title' => 'Pesanan Baru Dibayar!',
                'message' => "Pesanan #{$order->order_number} senilai Rp " . number_format($order->total_amount, 0, ',', '.') . " sudah dibayar. Silakan proses pesanan.",
                'type' => 'success',
            ]);

            Log::info('Midtrans Webhook - Order marked as paid', [
                'order_number' => $order->order_number,
            ]);
        }
    }

    private function markAsFailed(Order $order, array $payload): void
    {
        $wasPending = in_array($order->status, ['pending']);

        $order->update([
            'status' => 'expired',
            'midtrans_response' => $payload,
        ]);

        // Return stock
        if ($wasPending) {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }
            Log::info('Midtrans Webhook - Stock returned for order', [
                'order_number' => $order->order_number,
            ]);
        }
    }
}
