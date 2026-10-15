<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Snap;

class MidtransService
{
    /**
     * Boot and apply Midtrans configuration from services config.
     */
    public function __construct()
    {
        Config::$serverKey = (string) config('services.midtrans.server_key');
        Config::$clientKey = (string) config('services.midtrans.client_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Get existing Snap token or generate a new one for the given order.
     */
    public function getOrCreateSnapToken(Order $order): ?string
    {
        if (! empty($order->snap_token)) {
            return $order->snap_token;
        }

        $grossAmount = (int) round((float) $order->total_amount);
        if ($grossAmount <= 0) {
            return null;
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name ?: 'Pelanggan NONGKRONG.id',
            ],
            'enabled_payments' => [
                'gopay', 'shopeepay', 'qris', 'other_qris',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            return $snapToken;
        } catch (Exception $e) {
            Log::error('Midtrans getSnapToken failed: '.$e->getMessage(), [
                'order_code' => $order->order_code,
                'gross_amount' => $grossAmount,
            ]);

            return null;
        }
    }

    /**
     * Get or create a direct QRIS charge from Midtrans Core API.
     *
     * @return array{qr_url: ?string, qr_string: ?string}|null
     */
    public function getOrCreateQris(Order $order): ?array
    {
        $grossAmount = (int) round((float) $order->total_amount);
        if ($grossAmount <= 0) {
            return null;
        }

        $cacheKey = "midtrans_qris_{$order->order_code}";

        return Cache::remember($cacheKey, 900, function () use ($order, $grossAmount) {
            try {
                $charge = CoreApi::charge([
                    'payment_type' => 'qris',
                    'transaction_details' => [
                        'order_id' => $order->order_code,
                        'gross_amount' => $grossAmount,
                    ],
                    'customer_details' => [
                        'first_name' => $order->customer_name ?: 'Pelanggan NONGKRONG.id',
                    ],
                    'qris' => [
                        'acquirer' => 'gopay',
                    ],
                ]);

                $qrUrl = null;
                if (! empty($charge->actions)) {
                    foreach ($charge->actions as $action) {
                        if ($action->name === 'generate-qr-code') {
                            $qrUrl = $action->url;
                            break;
                        }
                    }
                }

                return [
                    'qr_url' => $qrUrl ?? ($charge->actions[0]->url ?? null),
                    'qr_string' => $charge->qr_string ?? null,
                ];
            } catch (Exception $e) {
                Log::warning('Midtrans getOrCreateQris failed: '.$e->getMessage(), [
                    'order_code' => $order->order_code,
                    'gross_amount' => $grossAmount,
                ]);

                return null;
            }
        });
    }

    /**
     * Verify Midtrans notification signature and process payment status transition.
     *
     * @param  array<string, mixed>  $payload
     */
    public function handleNotification(array $payload): ?Order
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? 'accept');

        if (empty($orderId)) {
            return null;
        }

        $serverKey = (string) config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        // Strictly enforce signature_key presence and verify SHA512 hash
        if (empty($signatureKey) || ! hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Midtrans signature verification failed or signature_key is missing', [
                'order_id' => $orderId,
                'received_signature' => $signatureKey,
            ]);

            return null;
        }

        $order = Order::where('order_code', $orderId)->first();
        if (! $order) {
            Log::warning('Midtrans webhook order not found: '.$orderId);

            return null;
        }

        // Validate nominal amount match between webhook and database order
        if ((int) round((float) $order->total_amount) !== (int) round((float) $grossAmount)) {
            Log::warning('Midtrans webhook gross_amount mismatch detected', [
                'order_id' => $orderId,
                'received_gross_amount' => $grossAmount,
                'expected_order_amount' => $order->total_amount,
            ]);

            return null;
        }

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
                ]);
            }
        } elseif ($transactionStatus === 'settlement') {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
            ]);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->update([
                'payment_status' => 'unpaid',
                'order_status' => 'cancelled',
            ]);
        } elseif ($transactionStatus === 'pending') {
            $order->update([
                'payment_status' => 'unpaid',
            ]);
        }

        return $order->fresh();
    }
}
