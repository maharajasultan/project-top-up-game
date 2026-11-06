@extends('layouts.admin')

@section('title', 'Log Notifikasi Email')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Log Notifikasi Email</h1>
        <p class="text-sm text-gray-500 mt-1">Riwayat pengiriman email otomatis ke pelanggan.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cari (Email / ID Order)</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari email atau id order..."
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div class="w-40">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tipe Order</label>
            <select name="order_type" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Tipe</option>
                <option value="topup" {{ request('order_type') == 'topup' ? 'selected' : '' }}>Top Up</option>
                <option value="joki" {{ request('order_type') == 'joki' ? 'selected' : '' }}>Joki</option>
            </select>
        </div>
        <div class="w-40">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status Pengiriman</label>
            <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Status</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'order_type', 'status']))
            <a href="{{ route('admin.notification-logs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4 w-48">Waktu</th>
                    <th class="p-4 w-40 text-center">Tipe Order</th>
                    <th class="p-4">Email Penerima</th>
                    <th class="p-4 text-center w-40">Status</th>
                    <th class="p-4">Keterangan Error (Jika Gagal)</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 text-gray-500 text-xs">
                        {{ $log->created_at->format('d M Y') }}<br>
                        <span class="font-bold text-gray-700">{{ $log->created_at->format('H:i:s') }}</span>
                    </td>
                    <td class="p-4 text-center">
                        <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold border uppercase {{ $log->order_type == 'topup' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-amber-50 text-amber-700 border-amber-100' }}">
                            {{ $log->order_type }}
                        </span>
                    </td>
                    <td class="p-4 font-medium text-gray-900">
                        {{ $log->recipient_email }}
                    </td>
                    <td class="p-4 text-center">
                        @if($log->status === 'sent')
                            <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold border bg-green-50 text-green-700 border-green-100">
                                <i class="fa-solid fa-check mr-1"></i> Terkirim
                            </span>
                        @else
                            <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold border bg-red-50 text-red-700 border-red-100">
                                <i class="fa-solid fa-xmark mr-1"></i> Gagal
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-xs text-red-500 font-mono">
                        {{ $log->error_message ?? '-' }}
                    </td>
                    <td class="p-4 text-right">
                        @if($log->order_type == 'topup')
                            <a href="{{ route('admin.orders-topup.show', $log->order_id) }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">Cek Order &rarr;</a>
                        @else
                            <a href="{{ route('admin.orders-joki.show', $log->order_id) }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">Cek Order &rarr;</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-gray-500">
                        <i class="fa-solid fa-envelope-open-text text-3xl text-gray-200 mb-3 block"></i>
                        Belum ada log notifikasi email.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
