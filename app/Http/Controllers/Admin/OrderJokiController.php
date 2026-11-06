<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderJoki;
use App\Models\Game;
use App\Models\Admin;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderJokiController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderJoki::with(['game', 'jokiType', 'jokiTier', 'loginMethod', 'assignedAdmin']);

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_email', 'like', "%{$request->search}%")
                  ->orWhere('customer_phone', 'like', "%{$request->search}%")
                  ->orWhere('account_email', 'like', "%{$request->search}%");
            });
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter game
        if ($request->game_id) {
            $query->where('game_id', $request->game_id);
        }

        // Filter periode per bulan
        if ($request->bulan && $request->tahun) {
            $query->whereYear('created_at', $request->tahun)
                  ->whereMonth('created_at', $request->bulan);
        } elseif ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();
        $games  = Game::where('available_joki', true)->orderBy('name')->get();

        // Summary per status
        $summaryQuery = OrderJoki::query();
        if ($request->bulan && $request->tahun) {
            $summaryQuery->whereYear('created_at', $request->tahun)
                         ->whereMonth('created_at', $request->bulan);
        }
        $summary = [
            'pending_payment' => (clone $summaryQuery)->where('status', 'pending_payment')->count(),
            'waiting_process' => (clone $summaryQuery)->where('status', 'waiting_process')->count(),
            'on_process'      => (clone $summaryQuery)->where('status', 'on_process')->count(),
            'completed'       => (clone $summaryQuery)->where('status', 'completed')->count(),
            'expired'         => (clone $summaryQuery)->where('status', 'expired')->count(),
            'failed'          => (clone $summaryQuery)->where('status', 'failed')->count(),
        ];

        $bulanList = collect(range(1, 12))->mapWithKeys(fn($m) => [$m => Carbon::create(null, $m)->isoFormat('MMMM')]);
        $tahunList = range(date('Y'), date('Y') - 3, -1);

        return view('admin.orders-joki.index', compact('orders', 'games', 'summary', 'bulanList', 'tahunList'));
    }

    public function show($id)
    {
        $order  = OrderJoki::with(['game', 'jokiType', 'jokiTier', 'loginMethod', 'assignedAdmin', 'notificationLogs'])->findOrFail($id);
        $admins = Admin::where('is_active', true)->orderBy('name')->get();
        return view('admin.orders-joki.show', compact('order', 'admins'));
    }

    public function update(Request $request, $id)
    {
        $order = OrderJoki::findOrFail($id);

        $request->validate([
            'status'          => 'required|in:pending_payment,waiting_process,on_process,completed,expired,failed',
            'internal_notes'  => 'nullable|string|max:1000',
            'assigned_admin_id' => 'nullable|exists:admins,id',
        ]);

        $oldStatus     = $order->status;
        $order->status = $request->status;

        if ($request->filled('internal_notes')) {
            $order->internal_notes = $request->internal_notes;
        }

        if ($request->has('assigned_admin_id')) {
            $order->assigned_admin_id = $request->assigned_admin_id ?: null;
        }

        // Catat waktu bayar jika status berubah dari pending ke waiting
        if ($oldStatus === 'pending_payment' && $order->status === 'waiting_process' && !$order->paid_at) {
            $order->paid_at = now();
        }

        $order->save();

        // Log aktivitas
        ActivityLog::create([
            'admin_id'    => Auth::guard('admin')->id(),
            'action'      => 'update_order_joki',
            'module'      => 'orders-joki',
            'description' => "Update status order {$order->invoice_number} dari {$oldStatus} → {$order->status}",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.orders-joki.show', $order->id)
            ->with('success', "Status order berhasil diubah dari {$oldStatus} menjadi {$order->status}.");
    }

    public function destroy($id)
    {
        $order   = OrderJoki::findOrFail($id);
        $invoice = $order->invoice_number;
        $order->delete();

        ActivityLog::create([
            'admin_id'    => Auth::guard('admin')->id(),
            'action'      => 'delete_order_joki',
            'module'      => 'orders-joki',
            'description' => "Hapus order Joki {$invoice}",
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('admin.orders-joki.index')
            ->with('success', 'Order joki berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = OrderJoki::with(['game', 'jokiType', 'jokiTier']);

        if ($request->bulan && $request->tahun) {
            $query->whereYear('created_at', $request->tahun)
                  ->whereMonth('created_at', $request->bulan);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders   = $query->orderBy('created_at')->get();
        $filename = 'order_joki_' . ($request->bulan ?? 'all') . '_' . ($request->tahun ?? date('Y')) . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Invoice', 'Game', 'Tipe Joki', 'Tier', 'Qty', 'Email Pelanggan', 'No.HP', 'Total Harga', 'Status', 'Waktu Bayar']);

            foreach ($orders as $o) {
                fputcsv($file, [
                    $o->created_at->format('Y-m-d H:i:s'),
                    $o->invoice_number,
                    $o->game->name ?? '-',
                    $o->jokiType->name ?? '-',
                    $o->jokiTier->tier_name ?? '-',
                    $o->qty,
                    $o->customer_email,
                    $o->customer_phone,
                    $o->final_total_price,
                    $o->status,
                    $o->paid_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
