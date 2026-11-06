<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderTopup;
use App\Models\Game;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderTopupController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderTopup::with(['game', 'product']);

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhere('user_game_id', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
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
        $games  = Game::where('available_topup', true)->orderBy('name')->get();

        // Summary per status pada filter bulan terpilih
        $summaryQuery = OrderTopup::query();
        if ($request->bulan && $request->tahun) {
            $summaryQuery->whereYear('created_at', $request->tahun)
                         ->whereMonth('created_at', $request->bulan);
        }
        $summary = [
            'pending' => (clone $summaryQuery)->where('status', 'pending')->count(),
            'success' => (clone $summaryQuery)->where('status', 'success')->count(),
            'expired' => (clone $summaryQuery)->where('status', 'expired')->count(),
            'failed'  => (clone $summaryQuery)->where('status', 'failed')->count(),
        ];

        $bulanList = collect(range(1, 12))->mapWithKeys(fn($m) => [$m => Carbon::create(null, $m)->isoFormat('MMMM')]);
        $tahunList = range(date('Y'), date('Y') - 3, -1);

        return view('admin.orders-topup.index', compact('orders', 'games', 'summary', 'bulanList', 'tahunList'));
    }

    public function show($id)
    {
        $order = OrderTopup::with(['game', 'product', 'notificationLogs'])->findOrFail($id);
        return view('admin.orders-topup.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = OrderTopup::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,success,expired,failed'
        ]);

        $oldStatus    = $order->status;
        $order->status = $request->status;

        // Jika dikonfirmasi sukses manual, catat waktu bayar
        if ($request->status === 'success' && !$order->paid_at) {
            $order->paid_at = now();
        }

        $order->save();

        // Log aktivitas
        ActivityLog::create([
            'admin_id'    => Auth::guard('admin')->id(),
            'action'      => 'update_order_topup',
            'module'      => 'orders-topup',
            'description' => "Update status order {$order->invoice_number} dari {$oldStatus} → {$order->status}",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.orders-topup.show', $order->id)
            ->with('success', "Status order berhasil diubah dari {$oldStatus} menjadi {$order->status}.");
    }

    public function destroy($id)
    {
        $order = OrderTopup::findOrFail($id);
        $invoice = $order->invoice_number;
        $order->delete();

        ActivityLog::create([
            'admin_id'    => Auth::guard('admin')->id(),
            'action'      => 'delete_order_topup',
            'module'      => 'orders-topup',
            'description' => "Hapus order Top Up {$invoice}",
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('admin.orders-topup.index')
            ->with('success', 'Order top up berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = OrderTopup::with(['game', 'product']);

        if ($request->bulan && $request->tahun) {
            $query->whereYear('created_at', $request->tahun)
                  ->whereMonth('created_at', $request->bulan);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders   = $query->orderBy('created_at')->get();
        $filename = 'order_topup_' . ($request->bulan ?? 'all') . '_' . ($request->tahun ?? date('Y')) . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Invoice', 'Game', 'Produk', 'User ID', 'Server ID', 'Username', 'Email', 'No.HP', 'Harga Jual', 'Harga Modal', 'Status', 'Waktu Bayar']);

            foreach ($orders as $o) {
                fputcsv($file, [
                    $o->created_at->format('Y-m-d H:i:s'),
                    $o->invoice_number,
                    $o->game->name ?? '-',
                    $o->product->name ?? '-',
                    $o->user_game_id,
                    $o->server_id ?? '-',
                    $o->username_result ?? '-',
                    $o->email,
                    $o->phone,
                    $o->final_price,
                    $o->product->modal_price ?? 0,
                    $o->status,
                    $o->paid_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
