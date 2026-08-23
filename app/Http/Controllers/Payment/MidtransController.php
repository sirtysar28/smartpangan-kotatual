<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * Payment method selection page
     */
    public function selectMethod(Order $order)
    {
        if (!$this->isOwner($order)) abort(403, 'Anda tidak memiliki akses ke pesanan ini.');

        if ($order->status === 'paid') return redirect()->route('payment.success', $order->id);
        if (in_array($order->status, ['cancelled', 'expired', 'delivered'])) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Pesanan ini sudah ' . $order->status_text . '.');
        }

        $order->load('items', 'umkm');
        return view('frontend.payment.select-method', compact('order'));
    }

    /**
     * Pay via Midtrans (online payment gateway)
     */
    public function pay(Order $order, MidtransService $midtrans)
    {
        if (!$this->isOwner($order)) abort(403, 'Anda tidak memiliki akses ke pesanan ini.');

        if ($order->status === 'paid') return redirect()->route('payment.success', $order->id);
        if (in_array($order->status, ['cancelled', 'expired'])) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Pesanan ini sudah ' . $order->status_text . '.');
        }

        // Set payment method to midtrans before proceeding
        if ($order->payment_method !== 'midtrans') {
            $order->update(['payment_method' => 'midtrans']);
        }

        $snapToken = $midtrans->createTransaction($order);
        if (!$snapToken) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Gagal memuat halaman pembayaran. Silakan coba lagi.');
        }

        return view('frontend.payment.pay', compact('order', 'snapToken'));
    }

    /**
     * Manual bank transfer confirmation
     */
    public function transfer(Order $order)
    {
        if (!$this->isOwner($order)) abort(403, 'Anda tidak memiliki akses ke pesanan ini.');

        if (!in_array($order->status, ['pending'])) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Pesanan tidak bisa dibayar dengan metode ini.');
        }

        $order->update([
            'payment_method' => 'transfer_bank',
            'payment_type'  => 'transfer_bank',
        ]);

        return view('frontend.payment.transfer', compact('order'));
    }

    public function success(Order $order)
    {
        if (!$this->isOwner($order)) abort(403);

        // Verify payment status from Midtrans
        $midtrans = app(MidtransService::class);
        $status = $midtrans->checkTransaction($order->order_number);

        if ($status && in_array($status->transaction_status, ['settlement', 'capture'])) {
            if ($order->status !== 'paid') {
                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_method' => $status->payment_type ?? $order->payment_method,
                    'payment_type'  => $status->payment_type ?? $order->payment_type,
                    'midtrans_transaction_id' => $status->transaction_id ?? null,
                    'midtrans_response' => (array) $status,
                ]);
            }
            return view('frontend.payment.success', compact('order'));
        }

        return redirect()->route('payment.pending', $order->id);
    }

    public function pending(Order $order)
    {
        if (!$this->isOwner($order)) abort(403);
        return view('frontend.payment.pending', compact('order'));
    }

    public function error(Order $order)
    {
        if (!$this->isOwner($order)) abort(403);
        return view('frontend.payment.error', compact('order'));
    }

    /**
     * Safe ownership check — use loose comparison to avoid string/int mismatch
     */
    private function isOwner(Order $order): bool
    {
        $authId = Auth::id();
        if ($authId === null) return false;
        return (int) $order->user_id === (int) $authId;
    }
}
