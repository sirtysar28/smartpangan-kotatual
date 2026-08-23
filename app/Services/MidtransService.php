<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Order $order): ?string
    {
        $order->load('user', 'items');

        $itemDetails = $order->items->map(function ($item) {
            return [
                'id' => (string) $item->product_id,
                'price' => (int) round($item->price),
                'quantity' => $item->quantity,
                'name' => substr($item->product_name, 0, 50),
            ];
        })->toArray();

        $itemDetails[] = [
            'id' => 'SHIPPING',
            'price' => (int) round($order->shipping_cost),
            'quantity' => 1,
            'name' => 'Ongkir (' . strtoupper($order->shipping_courier ?? 'JNE') . ')',
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) round($order->total_amount),
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone ?? '081234567890',
            ],
            'item_details' => $itemDetails,
            'enabled_payments' => [
                'qris', 'gopay', 'shopeepay', 'dana', 'linkaja',
                'bca_va', 'bni_va', 'bri_va', 'echannel',
                'credit_card',
            ],
            'callbacks' => [
                'finish' => route('payment.success', $order->id),
                'error' => route('payment.error', $order->id),
                'pending' => route('payment.pending', $order->id),
            ],
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
            return null;
        }
    }

    /**
     * Check transaction status from Midtrans API
     */
    public function checkTransaction(string $orderId): ?object
    {
        try {
            $status = Transaction::status($orderId);
            return json_decode(json_encode($status));
        } catch (\Exception $e) {
            \Log::error('Midtrans Status Check Error: ' . $e->getMessage(), [
                'order_id' => $orderId,
            ]);
            return null;
        }
    }
}
