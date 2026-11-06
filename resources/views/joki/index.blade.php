@extends('layouts.app')

@section('title', 'Pilih Game Joki')
@section('meta_description', 'Jasa Joki Rank terpercaya untuk Mobile Legends, Free Fire, dan PUBG Mobile di Maullstore.')

@section('content')
<div class="pt-24 pb-16 min-h-screen bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
            <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Jasa Joki</span>
        </nav>

        <!-- Header -->
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 bg-amber-50 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-3 border border-amber-100">
                <i class="fa-solid fa-trophy"></i>Jasa Joki
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">Pilih Game untuk Joki</h1>
            <p class="text-gray-500">Tim profesional kami siap boost rank, winrate, dan MMR akun game kamu</p>
        </div>

        <!-- Game Cards from Database -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($games as $game)
            <a href="/joki/{{ $game->slug }}" class="game-card bg-white border border-gray-200 rounded-2xl overflow-hidden hover:border-amber-300 group block">
                <div class="h-32 bg-gray-100 flex items-center justify-center relative overflow-hidden">
                    <img src="{{ $game->banner_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $game->name }}</h3>
                    <div class="space-y-1.5 mb-4">
                        @forelse($game->jokiTypes->take(3) as $type)
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fa-solid fa-check-circle text-green-500 text-xs w-4"></i>{{ $type->name }}
                        </div>
                        @empty
                        <p class="text-sm text-gray-400 italic">Segera tersedia</p>
                        @endforelse
                    </div>
                    <div class="flex items-center justify-center gap-2 bg-amber-500 group-hover:bg-amber-600 text-white text-sm font-semibold py-2.5 rounded-xl transition-all">
                        <i class="fa-solid fa-trophy text-xs"></i>Pesan Joki
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-20 text-gray-400">
                <i class="fa-solid fa-gamepad text-5xl mb-4 opacity-30"></i>
                <p class="font-medium">Belum ada game Joki yang tersedia</p>
            </div>
            @endforelse
        </div>

        <!-- SLA Info -->
        <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-green-50 rounded-2xl border border-green-100 text-center">
                <div class="text-2xl mb-2">⚡</div>
                <p class="font-semibold text-green-800 text-sm">Respon Cepat</p>
                <p class="text-xs text-green-600 mt-0.5">Admin merespons dalam <strong>10 menit</strong></p>
            </div>
            <div class="p-4 bg-primary-50 rounded-2xl border border-primary-100 text-center">
                <div class="text-2xl mb-2">🔒</div>
                <p class="font-semibold text-primary-800 text-sm">Data Aman</p>
                <p class="text-xs text-primary-600 mt-0.5">Password dienkripsi, tidak disimpan plain text</p>
            </div>
            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 text-center">
                <div class="text-2xl mb-2">🏆</div>
                <p class="font-semibold text-amber-800 text-sm">Tim Profesional</p>
                <p class="text-xs text-amber-600 mt-0.5">Dikerjakan oleh player top yang berpengalaman</p>
            </div>
        </div>

        <!-- Warning -->
        <div class="mt-6 p-5 bg-amber-50 rounded-2xl border border-amber-100 flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5"></i>
            <div>
                <p class="font-semibold text-amber-800 mb-1">Perhatian Sebelum Pesan Joki</p>
                <p class="text-sm text-amber-700 leading-relaxed">Pastikan kamu sudah <strong>mematikan Verifikasi 2 Langkah (V2L/2FA)</strong> di akun game kamu sebelum memesan joki. Joki tidak dapat diproses jika V2L masih aktif.</p>
            </div>
        </div>
    </div>
</div>
@endsection
