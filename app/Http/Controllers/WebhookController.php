<?php

namespace App\Http\Controllers;

use App\Models\OrderTopup;
use App\Models\OrderJoki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class WebhookController extends Controller
{
    /**
     * Handle Midtrans Webhook
     */
    public function midtrans(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received', $payload);

        // Validasi signature key
        $serverKey = Setting::get('midtrans_server_key');
        if (!$serverKey) {
            Log::error('Midtrans Server Key not configured.');
            return response()->json(['message' => 'Server Key not configured'], 500);
        }

        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($calculatedSignature !== $signatureKey) {
            Log::warning('Midtrans Webhook Invalid Signature', ['calculated' => $calculatedSignature, 'received' => $signatureKey]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $paymentType = $payload['payment_type'] ?? '';

        // Tentukan tipe order dan cari ordernya
        $order = null;
        $orderType = null;
        
        if (str_starts_with($orderId, 'TP-')) {
            $order = OrderTopup::where('invoice_number', $orderId)->first();
            $orderType = 'topup';
        } elseif (str_starts_with($orderId, 'JK-')) {
            $order = OrderJoki::where('invoice_number', $orderId)->first();
            $orderType = 'joki';
        }

        if (!$order) {
            Log::warning('Midtrans Webhook Order Not Found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Tentukan status akhir order
        $finalStatus = null;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $finalStatus = $orderType === 'topup' ? 'success' : 'waiting_process';
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $finalStatus = $transactionStatus === 'expire' ? 'expired' : 'failed';
        } elseif ($transactionStatus == 'pending') {
            $finalStatus = $orderType === 'topup' ? 'pending' : 'pending_payment';
        }

        if ($finalStatus && $order->status !== $finalStatus) {
            $order->status = $finalStatus;
            
            if ($finalStatus === 'success' || $finalStatus === 'waiting_process') {
                $order->paid_at = now();
                $order->payment_channel = $paymentType; // Update payment channel
            }
            
            $order->save();
            Log::info("Order {$orderId} status updated to {$finalStatus}");
            // Dispatch Email notification job here if payment is successful
            if ($finalStatus === 'success' || $finalStatus === 'waiting_process') {
                \App\Jobs\SendOrderNotificationJob::dispatch($order, $orderType);
            }
        }

        return response()->json(['message' => 'Webhook handled successfully']);
    }
}
