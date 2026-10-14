<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    /**
     * Handle incoming payment status webhook notifications from Midtrans.
     */
    public function handle(Request $request, MidtransService $midtransService): JsonResponse
    {
        $payload = $request->all();
        $order = $midtransService->handleNotification($payload);

        if (! $order) {
            return response()->json([
                'status' => 'ignored_or_invalid',
                'message' => 'Notification could not be processed or order not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'order_code' => $order->order_code,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
        ], 200);
    }
}
