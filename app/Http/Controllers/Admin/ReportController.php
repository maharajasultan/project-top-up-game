<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderTopup;
use App\Models\OrderJoki;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // Stats Topup
        $topupStats = [
            'total_orders' => OrderTopup::whereBetween('created_at', [$startDate, $endDate])->count(),
            'success_orders' => OrderTopup::where('status', 'success')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => OrderTopup::where('status', 'success')->whereBetween('created_at', [$startDate, $endDate])->sum('final_price'),
        ];

        // Stats Joki
        $jokiStats = [
            'total_orders' => OrderJoki::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_orders' => OrderJoki::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => OrderJoki::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate])->sum('final_total_price'),
        ];

        return view('admin.reports.index', compact('startDate', 'endDate', 'topupStats', 'jokiStats'));
    }

    public function export(Request $request)
    {
        $type = $request->type ?? 'topup';
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();
        
        $filename = "laporan_{$type}_" . $startDate->format('Ymd') . "-" . $endDate->format('Ymd') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'topup') {
                fputcsv($file, ['Tanggal', 'Invoice', 'Game', 'Produk', 'User Game ID', 'Harga Jual', 'Harga Modal', 'Status']);
                $orders = OrderTopup::with(['game', 'product'])->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at')->get();
                
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->invoice_number,
                        $order->game->name ?? '-',
                        $order->product->name ?? '-',
                        $order->user_game_id,
                        $order->final_price,
                        $order->product->modal_price ?? 0,
                        $order->status
                    ]);
                }
            } else {
                fputcsv($file, ['Tanggal', 'Invoice', 'Game', 'Tipe Joki', 'Email Pelanggan', 'Qty', 'Total Harga', 'Status']);
                $orders = OrderJoki::with(['game', 'jokiType'])->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at')->get();
                
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->invoice_number,
                        $order->game->name ?? '-',
                        $order->jokiType->name ?? '-',
                        $order->customer_email,
                        $order->qty,
                        $order->final_total_price,
                        $order->status
                    ]);
                }
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
