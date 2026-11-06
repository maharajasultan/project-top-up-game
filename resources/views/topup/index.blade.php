@extends('layouts.app')

@section('title', 'Pilih Game Top Up')
@section('meta_description', 'Pilih game untuk Top Up Diamond, UC, atau Membership. Mobile Legends, Free Fire, PUBG Mobile.')

@section('content')
<div class="pt-24 pb-16 min-h-screen bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
            <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Top Up</span>
        </nav>

        <!-- Header -->
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-3 border border-primary-100">
                <i class="fa-solid fa-bolt"></i>Top Up Game
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">Pilih Game Favorit</h1>
            <p class="text-gray-500">Pilih game yang ingin kamu top up. Proses otomatis &amp; instan!</p>
        </div>

        <!-- Game Cards from Database -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($games as $game)
            @php $minPrice = $game->topupProducts->min('price'); @endphp
            <a href="/topup/{{ $game->slug }}" class="game-card bg-white border border-gray-200 rounded-2xl overflow-hidden hover:border-primary-300 group block">
                <div class="h-32 bg-gray-100 flex items-center justify-center relative overflow-hidden">
                    <img src="{{ $game->banner_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $game->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $game->topupProducts->count() }} produk tersedia</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">Mulai dari</span>
                        @if($minPrice)
                            <span class="text-sm font-bold text-primary-600">Rp {{ number_format($minPrice, 0, ',', '.') }}</span>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-center justify-center gap-2 bg-primary-600 group-hover:bg-primary-700 text-white text-sm font-semibold py-2.5 rounded-xl transition-all">
                        <i class="fa-solid fa-bolt text-xs"></i>Top Up Sekarang
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-20 text-gray-400">
                <i class="fa-solid fa-gamepad text-5xl mb-4 opacity-30"></i>
                <p class="font-medium">Belum ada game Top Up yang tersedia</p>
            </div>
            @endforelse
        </div>

        <!-- Info Box -->
        <div class="mt-10 p-5 bg-primary-50 rounded-2xl border border-primary-100 flex items-start gap-4">
            <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-info text-primary-600"></i>
            </div>
            <div>
                <p class="font-semibold text-primary-800 mb-1">Informasi Penting</p>
                <p class="text-sm text-primary-700 leading-relaxed">Top Up bersifat otomatis dan instan. Pastikan User ID yang kamu masukkan sudah benar sebelum melakukan pembayaran. Batas waktu pembayaran adalah <strong>15 menit</strong> setelah order dibuat.</p>
            </div>
        </div>
    </div>
</div>
@endsection
