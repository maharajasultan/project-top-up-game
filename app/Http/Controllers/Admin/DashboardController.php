<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderTopup;
use App\Models\OrderJoki;
use App\Models\ChatConversation;
use App\Models\TopupProduct;
use App\Models\Game;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        /* ── Transaksi Hari Ini ── */
        $topupToday = OrderTopup::whereDate('created_at', $today)->count();
        $jokiToday  = OrderJoki::whereDate('created_at', $today)->count();

        /* ── Revenue Bulan Ini ── */
        $revenueTopupMonth = OrderTopup::where('status', 'success')
            ->where('created_at', '>=', $thisMonth)
            ->sum('final_price');

        $revenueJokiMonth = OrderJoki::where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->sum('final_total_price');

        /* ── Status Topup Bulan Ini ── */
        $topupStatusCount = [
            'pending' => OrderTopup::where('status', 'pending')->where('created_at', '>=', $thisMonth)->count(),
            'success' => OrderTopup::where('status', 'success')->where('created_at', '>=', $thisMonth)->count(),
            'expired' => OrderTopup::where('status', 'expired')->where('created_at', '>=', $thisMonth)->count(),
            'failed'  => OrderTopup::where('status', 'failed')->where('created_at', '>=', $thisMonth)->count(),
        ];

        /* ── Status Joki Bulan Ini ── */
        $jokiStatusCount = [
            'pending_payment' => OrderJoki::where('status', 'pending_payment')->where('created_at', '>=', $thisMonth)->count(),
            'waiting_process' => OrderJoki::where('status', 'waiting_process')->where('created_at', '>=', $thisMonth)->count(),
            'on_process'      => OrderJoki::where('status', 'on_process')->where('created_at', '>=', $thisMonth)->count(),
            'completed'       => OrderJoki::where('status', 'completed')->where('created_at', '>=', $thisMonth)->count(),
            'expired'         => OrderJoki::where('status', 'expired')->where('created_at', '>=', $thisMonth)->count(),
            'failed'          => OrderJoki::where('status', 'failed')->where('created_at', '>=', $thisMonth)->count(),
        ];

        /* ── SLA Joki (sudah bayar > 10 menit, belum diproses) ── */
        $slaJoki = OrderJoki::where('status', 'waiting_process')
            ->whereNotNull('paid_at')
            ->where('paid_at', '<=', Carbon::now()->subMinutes(10))
            ->count();

        /* ── Chat Belum Dibalas ── */
        $unreadChats = ChatConversation::where('status', 'open')
            ->whereHas('messages', fn($q) => $q->where('sender_type', 'customer')->where('is_read', false))
            ->count();

        /* ── Grafik 30 Hari ── */
        $chartData = $this->buildChartData();

        /* ── Top 5 Produk Topup Terlaris ── */
        $topProducts = OrderTopup::where('status', 'success')
            ->where('created_at', '>=', $thisMonth)
            ->selectRaw('product_id, COUNT(*) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->with('product')
            ->limit(5)
            ->get();

        /* ── Top 5 Joki Terlaris ── */
        $topJoki = OrderJoki::where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->selectRaw('joki_type_id, COUNT(*) as total')
            ->groupBy('joki_type_id')
            ->orderByDesc('total')
            ->with('jokiType')
            ->limit(5)
            ->get();

        /* ── Recent Orders ── */
        $recentTopup = OrderTopup::with('game', 'product')->latest()->limit(5)->get();
        $recentJoki  = OrderJoki::with('game', 'jokiType')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'topupToday', 'jokiToday',
            'revenueTopupMonth', 'revenueJokiMonth',
            'topupStatusCount', 'jokiStatusCount',
            'slaJoki', 'unreadChats',
            'chartData', 'topProducts', 'topJoki',
            'recentTopup', 'recentJoki'
        ));
    }

    /**
     * Build chart data for last 30 days.
     */
    private function buildChartData(): array
    {
        $labels    = [];
        $topupData = [];
        $jokiData  = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $labels[]    = Carbon::parse($date)->format('d M');
            $topupData[] = OrderTopup::whereDate('created_at', $date)->where('status', 'success')->count();
            $jokiData[]  = OrderJoki::whereDate('created_at', $date)->where('status', 'completed')->count();
        }

        return compact('labels', 'topupData', 'jokiData');
    }
}
