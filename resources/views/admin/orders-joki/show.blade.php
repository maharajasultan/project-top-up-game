@extends('layouts.admin')

@section('title', 'Detail Transaksi Joki')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders-joki.index') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Daftar Transaksi</a>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi Joki</h1>
        <span class="font-mono text-sm bg-gray-100 text-gray-700 py-1.5 px-3 rounded-lg border border-gray-200">{{ $order->invoice_number }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Joki Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i> Info Layanan Joki
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Game</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->game->name }}</dd>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Tipe Joki</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->jokiType->name ?? '-' }}</dd>
                </div>
                @if($order->jokiTier)
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Tier / Rank</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->jokiTier->tier_name }}</dd>
                </div>
                @endif
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Jumlah (Qty)</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->qty }} {{ $order->jokiType->unit_name ?? 'unit' }}</dd>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Metode Login</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->loginMethod->method_name ?? '-' }}</dd>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">V2L (Mode Aman)</dt>
                    <dd class="text-sm font-bold {{ $order->v2l_status ? 'text-green-600' : 'text-red-600' }}">
                        {{ $order->v2l_status ? 'Aktif' : 'Nonaktif' }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-2xl border border-orange-100 shadow-sm p-6" x-data="{ showPass: false }">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-lock text-orange-500"></i> Info Akun Game
                <span class="ml-auto text-xs font-medium text-orange-600 bg-orange-50 py-1 px-2 rounded-lg border border-orange-100">Rahasia</span>
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Email Akun</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->account_email }}</dd>
                </div>
                <div class="flex justify-between py-3 items-center">
                    <dt class="text-sm text-gray-500 font-medium">Password Akun</dt>
                    <dd class="flex items-center gap-2">
                        <span x-show="!showPass" class="text-sm font-mono text-gray-400">••••••••••</span>
                        <span x-show="showPass" class="text-sm font-mono font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-lg">{{ $order->account_password }}</span>
                        <button type="button" @click="showPass = !showPass" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </dd>
                </div>
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
                    <dd class="text-sm font-bold text-gray-900">{{ $order->customer_email }}</dd>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">No. Telepon</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $order->customer_phone }}</dd>
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
                    <dt class="text-sm text-gray-500 font-medium">Harga per Unit</dt>
                    <dd class="text-sm font-semibold text-gray-900">Rp {{ number_format($order->original_price_per_unit, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Qty</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ $order->qty }}</dd>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm text-gray-500 font-medium">Diskon</dt>
                    <dd class="text-sm font-semibold text-green-600">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</dd>
                </div>
                @endif
                <div class="flex justify-between py-3 border-b border-gray-50">
                    <dt class="text-sm font-bold text-gray-800">Total Bayar</dt>
                    <dd class="text-lg font-extrabold text-primary-600">Rp {{ number_format($order->final_total_price, 0, ',', '.') }}</dd>
                </div>
                @if($order->payment_channel)
                <div class="flex justify-between py-3">
                    <dt class="text-sm text-gray-500 font-medium">Metode Bayar</dt>
                    <dd class="text-sm font-bold text-gray-900 uppercase">{{ $order->payment_channel }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">Status & Update</h3>
            @php
                $statusConfig = [
                    'pending_payment' => ['bg-yellow-50 text-yellow-700 border-yellow-200', 'fa-clock', 'Menunggu Pembayaran'],
                    'waiting_process' => ['bg-blue-50 text-blue-700 border-blue-200', 'fa-hourglass-half', 'Menunggu Proses'],
                    'on_process' => ['bg-purple-50 text-purple-700 border-purple-200', 'fa-spinner fa-spin', 'Sedang Dikerjakan'],
                    'completed' => ['bg-green-50 text-green-700 border-green-200', 'fa-circle-check', 'Selesai'],
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

            <form action="{{ route('admin.orders-joki.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <label class="block text-xs font-semibold text-gray-600 mb-2">Ubah Status</label>
                <select name="status" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 mb-3">
                    <option value="pending_payment" {{ $order->status == 'pending_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="waiting_process" {{ $order->status == 'waiting_process' ? 'selected' : '' }}>Menunggu Proses</option>
                    <option value="on_process" {{ $order->status == 'on_process' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="expired" {{ $order->status == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="failed" {{ $order->status == 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
                
                <label class="block text-xs font-semibold text-gray-600 mb-2">Catatan Internal</label>
                <textarea name="internal_notes" rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 mb-3" placeholder="Catatan khusus admin...">{{ $order->internal_notes }}</textarea>
                
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Quick Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-3">
            <h3 class="font-bold text-gray-900">Info Lainnya</h3>
            <div>
                <p class="text-xs text-gray-500 mb-1">Waktu Dibuat</p>
                <p class="text-sm font-semibold text-gray-800">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            @if($order->paid_at)
            <div>
                <p class="text-xs text-gray-500 mb-1">Waktu Bayar</p>
                <p class="text-sm font-semibold text-gray-800">{{ $order->paid_at->format('d M Y, H:i') }}</p>
                @if($order->status === 'waiting_process')
                <p class="text-xs text-gray-500 mt-1">Menunggu {{ now()->diffForHumans($order->paid_at, true) }}</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Delete -->
        <form action="{{ route('admin.orders-joki.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini? Data tidak bisa dikembalikan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2.5 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 border border-red-100">
                <i class="fa-solid fa-trash-can"></i> Hapus Transaksi
            </button>
        </form>
    </div>
</div>
@endsection
