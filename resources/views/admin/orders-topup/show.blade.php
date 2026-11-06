@extends('layouts.admin')

@section('title', 'Detail Transaksi Top Up')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders-topup.index') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Daftar Transaksi</a>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi Top Up</h1>
        <span class="font-mono text-sm bg-gray-100 text-gray-700 py-1.5 px-3 rounded-lg border border-gray-200">{{ $order->invoice_number }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-blue-500"></i> Info Top Up
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Game</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->game->name }}</dd>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Produk</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->product->name ?? '-' }}</dd>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">ID Game</dt>
                    <dd class="text-sm font-bold text-gray-900 font-mono">{{ $order->user_game_id }}</dd>
                </div>
                @if($order->server_id)
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Server ID</dt>
                    <dd class="text-sm font-bold text-gray-900 font-mono">{{ $order->server_id }}</dd>
                </div>
                @endif
                @if($order->username_result)
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Username In-Game</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->username_result }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Customer Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user text-gray-500"></i> Info Pelanggan
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Email</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->email }}</dd>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">No. Telepon</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->phone }}</dd>
                </div>
            </dl>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-wallet text-green-500"></i> Info Pembayaran
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Harga Normal</dt>
                    <dd class="text-sm font-semibold text-gray-900">Rp {{ number_format($order->original_price, 0, ',', '.') }}</dd>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Diskon</dt>
                    <dd class="text-sm font-semibold text-green-600">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</dd>
                </div>
                @endif
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm font-bold text-gray-800">Total Bayar</dt>
                    <dd class="text-lg font-extrabold text-primary-600">Rp {{ number_format($order->final_price, 0, ',', '.') }}</dd>
                </div>
                @if($order->payment_channel)
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Metode Bayar</dt>
                    <dd class="text-sm font-bold text-gray-900 uppercase">{{ $order->payment_channel }}</dd>
                </div>
                @endif
                @if($order->paid_at)
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Tanggal Bayar</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->paid_at->format('d M Y H:i') }}</dd>
                </div>
                @endif
                @if($order->midtrans_order_id)
                <div class="flex justify-between py-3">
                    <dt class="text-sm text-gray-500 font-medium">Midtrans Order ID</dt>
                    <dd class="text-xs font-mono text-gray-700">{{ $order->midtrans_order_id }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">Status Transaksi</h3>
            @php
                $statusConfig = [
                    'pending' => ['bg-yellow-50 text-yellow-700 border-yellow-200', 'fa-clock', 'Menunggu Pembayaran'],
                    'success' => ['bg-green-50 text-green-700 border-green-200', 'fa-circle-check', 'Berhasil'],
                    'expired' => ['bg-gray-100 text-gray-600 border-gray-200', 'fa-calendar-xmark', 'Expired'],
                    'failed' => ['bg-red-50 text-red-700 border-red-200', 'fa-circle-xmark', 'Gagal'],
                ];
                [$cls, $icon, $label] = $statusConfig[$order->status] ?? ['bg-gray-100 text-gray-600 border-gray-200', 'fa-question', $order->status];
            @endphp
            <div class="flex items-center gap-3 p-4 rounded-xl border {{ $cls }} mb-5">
                <i class="fa-solid {{ $icon }} text-xl"></i>
                <div>
                    <p class="font-bold text-sm">{{ $label }}</p>
                    <p class="text-xs opacity-75">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <!-- Status Update Form -->
            <form action="{{ route('admin.orders-topup.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <label class="block text-xs font-semibold text-gray-600 mb-2">Ubah Status Secara Manual</label>
                <select name="status" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 mb-3">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="success" {{ $order->status == 'success' ? 'selected' : '' }}>Sukses</option>
                    <option value="expired" {{ $order->status == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="failed" {{ $order->status == 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Quick Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-3">
            <h3 class="font-bold text-gray-900">Informasi Lainnya</h3>
            <div>
                <p class="text-xs text-gray-500 mb-1">Email Status</p>
                <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold border {{ $order->email_sent_status === 'sent' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-gray-100 text-gray-600 border-gray-200' }}">
                    Email: {{ ucfirst($order->email_sent_status) }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Waktu Dibuat</p>
                <p class="text-sm font-semibold text-gray-800">{{ $order->created_at->format('d M Y, H:i:s') }}</p>
            </div>
            @if($order->expired_at)
            <div>
                <p class="text-xs text-gray-500 mb-1">Kadaluarsa</p>
                <p class="text-sm font-semibold text-gray-800">{{ $order->expired_at->format('d M Y, H:i:s') }}</p>
            </div>
            @endif
        </div>

        <!-- Delete -->
        <form action="{{ route('admin.orders-topup.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini? Data tidak bisa dikembalikan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2.5 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 border border-red-100">
                <i class="fa-solid fa-trash-can"></i> Hapus Transaksi
            </button>
        </form>
    </div>
</div>
@endsection
