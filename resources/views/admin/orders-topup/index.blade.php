@extends('layouts.admin')

@section('title', 'Transaksi Top Up')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Transaksi Top Up</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar semua transaksi top up dari pelanggan.</p>
    </div>
</div>

<!-- Stats Summary -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-yellow-50 rounded-full opacity-50"></div>
        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Pending</p>
        <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($summary['pending']) }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-green-50 rounded-full opacity-50"></div>
        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Sukses</p>
        <h3 class="text-2xl font-extrabold text-green-600">{{ number_format($summary['success']) }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-50 rounded-full opacity-50"></div>
        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Gagal</p>
        <h3 class="text-2xl font-extrabold text-red-600">{{ number_format($summary['failed']) }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-gray-50 rounded-full opacity-50"></div>
        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Expired</p>
        <h3 class="text-2xl font-extrabold text-gray-600">{{ number_format($summary['expired']) }}</h3>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
    <form method="GET" class="flex flex-wrap gap-3 items-end w-full lg:w-auto">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice, ID Game, Email, No HP..."
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
        <div class="w-36">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
            <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Sukses</option>
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
            <a href="{{ route('admin.orders-topup.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
    </form>
    
    <div>
        <a href="{{ route('admin.orders-topup.export', request()->query()) }}" class="bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 font-semibold py-2.5 px-5 rounded-xl text-sm transition-colors flex items-center gap-2 whitespace-nowrap">
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
                    <th class="p-4">Produk</th>
                    <th class="p-4">Pelanggan</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Tanggal</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4">
                        <p class="font-bold text-gray-900 font-mono text-xs">{{ $order->invoice_number }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $order->game->name }}</p>
                        <p class="text-xs text-gray-400">ID: {{ $order->user_game_id }}{{ $order->server_id ? ' / ' . $order->server_id : '' }}</p>
                    </td>
                    <td class="p-4">
                        <p class="font-semibold text-gray-800 text-sm">{{ $order->product->name ?? 'Produk dihapus' }}</p>
                    </td>
                    <td class="p-4">
                        <p class="text-gray-800 font-medium">{{ $order->email }}</p>
                        <p class="text-xs text-gray-500">{{ $order->phone }}</p>
                    </td>
                    <td class="p-4 text-right">
                        <p class="font-bold text-gray-900">Rp {{ number_format($order->final_price, 0, ',', '.') }}</p>
                    </td>
                    <td class="p-4 text-center">
                        @php
                            $statusConfig = [
                                'pending' => ['bg-yellow-50 text-yellow-700 border-yellow-100', 'Pending'],
                                'success' => ['bg-green-50 text-green-700 border-green-100', 'Sukses'],
                                'expired' => ['bg-gray-100 text-gray-600 border-gray-200', 'Expired'],
                                'failed' => ['bg-red-50 text-red-700 border-red-100', 'Gagal'],
                            ];
                            [$cls, $label] = $statusConfig[$order->status] ?? ['bg-gray-100 text-gray-600 border-gray-200', $order->status];
                        @endphp
                        <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold border {{ $cls }}">{{ $label }}</span>
                    </td>
                    <td class="p-4 text-center text-xs text-gray-500">
                        {{ $order->created_at->format('d M Y') }}<br>
                        {{ $order->created_at->format('H:i') }}
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.orders-topup.show', $order) }}" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors inline-flex" title="Detail">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-gray-500">
                        <i class="fa-solid fa-inbox text-3xl text-gray-200 mb-3 block"></i>
                        Belum ada transaksi top up.
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
