@extends('layouts.admin')

@section('title', 'Transaksi Joki')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Transaksi Joki</h1>
    <p class="text-sm text-gray-500 mt-1">Daftar semua order jasa joki dari pelanggan.</p>
</div>

<!-- Stats Summary -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">M. Bayar</p>
        <h3 class="text-xl font-extrabold text-yellow-600">{{ number_format($summary['pending_payment']) }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">M. Proses</p>
        <h3 class="text-xl font-extrabold text-blue-600">{{ number_format($summary['waiting_process']) }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Dikerjakan</p>
        <h3 class="text-xl font-extrabold text-purple-600">{{ number_format($summary['on_process']) }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Selesai</p>
        <h3 class="text-xl font-extrabold text-green-600">{{ number_format($summary['completed']) }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Gagal</p>
        <h3 class="text-xl font-extrabold text-red-600">{{ number_format($summary['failed']) }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Expired</p>
        <h3 class="text-xl font-extrabold text-gray-600">{{ number_format($summary['expired']) }}</h3>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 flex flex-col xl:flex-row xl:items-end justify-between gap-4">
    <form method="GET" class="flex flex-wrap gap-3 items-end w-full xl:w-auto">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice, Email, No HP..."
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div class="w-40">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Game</label>
            <select name="game_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Game</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-44">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
            <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua</option>
                <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                <option value="waiting_process" {{ request('status') == 'waiting_process' ? 'selected' : '' }}>Menunggu Proses</option>
                <option value="on_process" {{ request('status') == 'on_process' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>
        <div class="w-32">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Bulan</label>
            <select name="bulan" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua</option>
                @foreach($bulanList as $num => $name)
                    <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-28">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tahun</label>
            <select name="tahun" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua</option>
                @foreach($tahunList as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'game_id', 'status', 'bulan', 'tahun']))
            <a href="{{ route('admin.orders-joki.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
    </form>
    
    <div>
        <a href="{{ route('admin.orders-joki.export', request()->query()) }}" class="bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 font-semibold py-2.5 px-5 rounded-xl text-sm transition-colors flex items-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-file-csv"></i> Export CSV
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4">Invoice / Game</th>
                    <th class="p-4">Layanan</th>
                    <th class="p-4">Pelanggan</th>
                    <th class="p-4 text-center">Qty</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($orders as $order)
                @php
                    $statusConfig = [
                        'pending_payment' => ['bg-yellow-50 text-yellow-700 border-yellow-100', 'Menunggu Bayar'],
                        'waiting_process' => ['bg-blue-50 text-blue-700 border-blue-100', 'Menunggu Proses'],
                        'on_process' => ['bg-purple-50 text-purple-700 border-purple-100', 'Dikerjakan'],
                        'completed' => ['bg-green-50 text-green-700 border-green-100', 'Selesai'],
                        'expired' => ['bg-gray-100 text-gray-600 border-gray-200', 'Expired'],
                        'failed' => ['bg-red-50 text-red-700 border-red-100', 'Gagal'],
                    ];
                    [$cls, $label] = $statusConfig[$order->status] ?? ['bg-gray-100 text-gray-600', $order->status];
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors {{ $order->status === 'waiting_process' && $order->paid_at && now()->diffInMinutes($order->paid_at) > 10 ? 'ring-1 ring-red-200 ring-inset' : '' }}">
                    <td class="p-4">
                        <p class="font-bold text-gray-900 font-mono text-xs">{{ $order->invoice_number }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $order->game->name }}</p>
                    </td>
                    <td class="p-4">
                        <p class="font-semibold text-gray-800 text-sm">{{ $order->jokiType->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $order->jokiTier->tier_name ?? 'No Tier' }}</p>
                    </td>
                    <td class="p-4">
                        <p class="text-gray-800 font-medium">{{ $order->customer_email }}</p>
                        <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
                    </td>
                    <td class="p-4 text-center font-bold text-gray-800">
                        {{ $order->qty }}
                    </td>
                    <td class="p-4 text-right">
                        <p class="font-bold text-gray-900">Rp {{ number_format($order->final_total_price, 0, ',', '.') }}</p>
                    </td>
                    <td class="p-4 text-center">
                        <span class="inline-flex py-1 px-2 rounded-md text-[10px] font-bold border {{ $cls }}">{{ $label }}</span>
                        @if($order->status === 'waiting_process' && $order->paid_at && now()->diffInMinutes($order->paid_at) > 10)
                            <p class="text-[10px] text-red-500 font-bold mt-1">⚠ Lewat SLA!</p>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.orders-joki.show', $order) }}" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors inline-flex" title="Detail">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-gray-500">
                        <i class="fa-solid fa-inbox text-3xl text-gray-200 mb-3 block"></i>
                        Belum ada transaksi joki.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $orders->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
