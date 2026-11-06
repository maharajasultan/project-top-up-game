<?php

namespace App\Http\Controllers;

use App\Models\OrderTopup;
use App\Models\OrderJoki;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Fetch Topup Orders
        $topups = OrderTopup::where('user_id', $userId)
            ->with(['game', 'product'])
            ->get()
            ->map(function ($order) {
                $order->transaction_type = 'topup';
                $order->title = $order->product ? $order->product->name : 'Top Up';
                $order->total = $order->final_price;
                return $order;
            });

        // Fetch Joki Orders
        $jokis = OrderJoki::where('user_id', $userId)
            ->with(['game', 'jokiType', 'jokiTier'])
            ->get()
            ->map(function ($order) {
                $order->transaction_type = 'joki';
                $tierName = $order->jokiTier ? ' - ' . $order->jokiTier->tier_name : '';
                $order->title = ($order->jokiType ? $order->jokiType->name : 'Joki') . $tierName;
                $order->total = $order->final_total_price;

                // Normalisasi status joki agar konsisten dengan topup di frontend
                // pending_payment → pending (menunggu bayar)
                // waiting_process → success (bayar sudah, menunggu diproses)
                if ($order->status == 'pending_payment') {
                    $order->status = 'pending';
                }
                return $order;
            });

        // Merge and sort by created_at descending
        $transactions = $topups->merge($jokis)->sortByDesc('created_at');

        return view('transaction-history', compact('transactions'));
    }
}
