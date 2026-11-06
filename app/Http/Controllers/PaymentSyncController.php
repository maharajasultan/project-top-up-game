<?php

namespace App\Http\Controllers;

use App\Models\OrderTopup;
use App\Models\OrderJoki;
use App\Models\OrderTopup as TopupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOrderNotificationJob;

/**
 * PaymentSyncController
 *
 * Mengecek status transaksi langsung ke Midtrans API dan memperbarui database.
 * Digunakan sebagai fallback ketika webhook Midtrans tidak bisa hit server lokal.
 */
class PaymentSyncController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey   = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    /**
     * Sync status dari Midtrans API dan update database.
     * Dipanggil dari frontend setelah callback onSuccess Snap.
     */
    public function sync(Request $request)
    {
        $request->validate([
            'invoice' => 'required|string|max:50',
        ]);

        $invoice = trim($request->invoice);

        // Cari order (topup atau joki)
        $order     = null;
        $orderType = null;

        if (str_starts_with($invoice, 'TP-')) {
            $order     = OrderTopup::where('invoice_number', $invoice)->first();
            $orderType = 'topup';
        } elseif (str_starts_with($invoice, 'JK-')) {
            $order     = OrderJoki::where('invoice_number', $invoice)->first();
            $orderType = 'joki';
        }

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan.',
            ], 404);
        }

        // Cek status ke Midtrans API
        try {
            $status = \Midtrans\Transaction::status($invoice);

            $transactionStatus = $status->transaction_status ?? null;
            $paymentType       = $status->payment_type ?? null;

            Log::info("PaymentSync: {$invoice} → Midtrans status: {$transactionStatus}");

            // Map status Midtrans ke status internal
            $finalStatus = $this->mapStatus($transactionStatus, $orderType);

            if ($finalStatus && $order->status !== $finalStatus) {
                $order->status = $finalStatus;

                if (in_array($finalStatus, ['success', 'waiting_process'])) {
                    $order->paid_at         = now();
                    $order->payment_channel = $paymentType;
                }

                $order->save();
                Log::info("PaymentSync: {$invoice} status diperbarui ke {$finalStatus}");

                // Kirim notifikasi email jika berhasil
                if (in_array($finalStatus, ['success', 'waiting_process'])) {
                    SendOrderNotificationJob::dispatch($order, $orderType);
                }
            }

            return response()->json([
                'success'       => true,
                'status'        => $finalStatus ?? $order->status,
                'raw_status'    => $transactionStatus,
                'invoice'       => $invoice,
                'order_type'    => $orderType,
                'paid_at'       => $order->paid_at?->format('d M Y, H:i'),
                'payment_method'=> $order->payment_channel ?? $paymentType,
            ]);

        } catch (\Exception $e) {
            Log::error("PaymentSync Error for {$invoice}: " . $e->getMessage());

            // Jika Midtrans tidak bisa dihubungi, kembalikan status dari DB
            return response()->json([
                'success'    => false,
                'message'    => 'Gagal mengecek status ke Midtrans: ' . $e->getMessage(),
                'status'     => $order->status,
                'invoice'    => $invoice,
                'order_type' => $orderType,
            ], 500);
        }
    }

    /**
     * Halaman detail transaksi untuk customer.
     */
    public function detail($invoice)
    {
        $order     = null;
        $orderType = null;

        if (str_starts_with($invoice, 'TP-')) {
            $order     = OrderTopup::with(['game', 'product'])->where('invoice_number', $invoice)->firstOrFail();
            $orderType = 'topup';
        } elseif (str_starts_with($invoice, 'JK-')) {
            $order     = OrderJoki::with(['game', 'jokiType', 'jokiTier'])->where('invoice_number', $invoice)->firstOrFail();
            $orderType = 'joki';
        } else {
            abort(404);
        }

        // Pastikan hanya pemilik yang bisa lihat (jika sudah login)
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        return view('transaction-detail', compact('order', 'orderType'));
    }

    /**
     * Map status Midtrans ke status internal
     */
    private function mapStatus(?string $transactionStatus, string $orderType): ?string
    {
        if (!$transactionStatus) return null;

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            return $orderType === 'topup' ? 'success' : 'waiting_process';
        }

        if (in_array($transactionStatus, ['cancel', 'deny'])) {
            return 'failed';
        }

        if ($transactionStatus === 'expire') {
            return 'expired';
        }

        if ($transactionStatus === 'pending') {
            return $orderType === 'topup' ? 'pending' : 'pending_payment';
        }

        return null;
    }
}
