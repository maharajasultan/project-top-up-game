@extends('layouts.app')

@section('title', 'Maullstore')
@section('meta_description', 'Maullstore - Platform Top Up Game & Jasa Joki terpercaya. Top Up Diamond Mobile Legends, Free Fire, PUBG Mobile. Proses cepat & aman.')

@section('content')

<!-- ===== HERO SECTION ===== -->
<section class="relative pt-24 pb-16 md:pt-32 md:pb-24 bg-white overflow-hidden">
    <!-- Subtle background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-50 rounded-full opacity-60"></div>
        <div class="absolute top-1/2 -left-20 w-64 h-64 bg-indigo-50 rounded-full opacity-40"></div>
        <div class="absolute bottom-0 right-1/4 w-48 h-48 bg-purple-50 rounded-full opacity-30"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex flex-col items-center justify-center gap-12 lg:gap-16">

            <!-- Text Content -->
            <div class="w-full animate-fade-in">
                <div class="inline-flex items-center justify-center gap-2 bg-primary-50 text-primary-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-5 border border-primary-100">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse-slow"></span>
                    Layanan Online 08.00 – 22.00 WIB
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-5 mx-auto">
                    Top Up & Joki Game<br>
                    <span class="gradient-text">Cepat, Aman & Murah</span>
                </h1>
                <p class="text-lg text-gray-500 leading-relaxed mb-8 max-w-2xl mx-auto">
                    Platform terpercaya untuk Top Up Diamond, UC, Membership, dan Jasa Joki Rank untuk Mobile Legends, Free Fire, dan PUBG Mobile.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="/topup" class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        <i class="fa-solid fa-bolt"></i>Top Up Sekarang
                    </a>
                    <a href="/joki" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3.5 rounded-xl transition-all border border-gray-200 hover:border-primary-300 hover:text-primary-600">
                        <i class="fa-solid fa-trophy"></i>Jasa Joki
                    </a>
                </div>

                <!-- Stats -->
                <div class="flex flex-wrap gap-6 justify-center mt-10 pt-8 border-t border-gray-100 max-w-2xl mx-auto">
                    <div>
                        <p class="text-2xl font-extrabold text-gray-900">10.000+</p>
                        <p class="text-sm text-gray-500">Transaksi Sukses</p>
                    </div>
                    <div class="w-px bg-gray-200 hidden sm:block"></div>
                    <div>
                        <p class="text-2xl font-extrabold text-gray-900">3 Game</p>
                        <p class="text-sm text-gray-500">Tersedia</p>
                    </div>
                    <div class="w-px bg-gray-200 hidden sm:block"></div>
                    <div>
                        <p class="text-2xl font-extrabold text-gray-900">< 5 Menit</p>
                        <p class="text-sm text-gray-500">Proses Top Up</p>
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>

<!-- ===== GAME SECTION ===== -->
<section class="py-16 bg-gray-50" id="layanan">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-1.5 text-primary-600 text-sm font-semibold mb-3">
                <i class="fa-solid fa-gamepad"></i>Game Tersedia
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-3">Pilih Game Favoritmu</h2>
            <p class="text-gray-500 max-w-lg mx-auto">Top Up Diamond, UC, atau pesan jasa Joki untuk ketiga game populer ini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Mobile Legends -->
            <div class="game-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm group cursor-pointer">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-md overflow-hidden bg-gray-100">
                        <img src="{{ url('/images/games/ml_banner.png') }}" alt="Mobile Legends" class="w-full h-full object-cover">
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 px-2.5 py-1 rounded-full border border-green-100">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                    </span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Mobile Legends</h3>
                <p class="text-sm text-gray-500 mb-5">Diamond, Weekly Pass, Twilight Pass & lebih banyak</p>
                <div class="flex gap-2">
                    <a href="/topup/mobile-legends" class="flex-1 text-center bg-primary-50 hover:bg-primary-600 text-primary-700 hover:text-white text-sm font-semibold py-2.5 rounded-xl transition-all border border-primary-100 hover:border-primary-600">
                        <i class="fa-solid fa-bolt mr-1.5"></i>Top Up
                    </a>
                    <a href="/joki/mobile-legends" class="flex-1 text-center bg-gray-50 hover:bg-gray-800 text-gray-700 hover:text-white text-sm font-semibold py-2.5 rounded-xl transition-all border border-gray-200 hover:border-gray-800">
                        <i class="fa-solid fa-trophy mr-1.5"></i>Joki
                    </a>
                </div>
            </div>

            <!-- Free Fire -->
            <div class="game-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm group cursor-pointer relative">
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-red-500 px-2.5 py-1 rounded-full shadow-sm">
                        🔥 Popular
                    </span>
                </div>
                <div class="flex items-start justify-between mb-5">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-md overflow-hidden bg-gray-100">
                        <img src="{{ url('/images/games/ff_banner.png') }}" alt="Free Fire" class="w-full h-full object-cover">
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Free Fire</h3>
                <p class="text-sm text-gray-500 mb-5">Diamond, Membership Bonanza, Pengaman Rank</p>
                <div class="flex gap-2">
                    <a href="/topup/free-fire" class="flex-1 text-center bg-primary-50 hover:bg-primary-600 text-primary-700 hover:text-white text-sm font-semibold py-2.5 rounded-xl transition-all border border-primary-100 hover:border-primary-600">
                        <i class="fa-solid fa-bolt mr-1.5"></i>Top Up
                    </a>
                    <a href="/joki/free-fire" class="flex-1 text-center bg-gray-50 hover:bg-gray-800 text-gray-700 hover:text-white text-sm font-semibold py-2.5 rounded-xl transition-all border border-gray-200 hover:border-gray-800">
                        <i class="fa-solid fa-trophy mr-1.5"></i>Joki
                    </a>
                </div>
            </div>

            <!-- PUBG Mobile -->
            <div class="game-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm group cursor-pointer">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-md overflow-hidden bg-gray-100">
                        <img src="{{ url('/images/games/pubg_banner.png') }}" alt="PUBG Mobile" class="w-full h-full object-cover">
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 px-2.5 py-1 rounded-full border border-green-100">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                    </span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">PUBG Mobile</h3>
                <p class="text-sm text-gray-500 mb-5">Unknown Cash (UC) & Rank Boosting</p>
                <div class="flex gap-2">
                    <a href="/topup/pubg-mobile" class="flex-1 text-center bg-primary-50 hover:bg-primary-600 text-primary-700 hover:text-white text-sm font-semibold py-2.5 rounded-xl transition-all border border-primary-100 hover:border-primary-600">
                        <i class="fa-solid fa-bolt mr-1.5"></i>Top Up
                    </a>
                    <a href="/joki/pubg-mobile" class="flex-1 text-center bg-gray-50 hover:bg-gray-800 text-gray-700 hover:text-white text-sm font-semibold py-2.5 rounded-xl transition-all border border-gray-200 hover:border-gray-800">
                        <i class="fa-solid fa-trophy mr-1.5"></i>Joki
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== HOW TO ORDER ===== -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-1.5 text-primary-600 text-sm font-semibold mb-3">
                <i class="fa-solid fa-list-ol"></i>Cara Pesan
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-3">Mudah dalam 4 Langkah</h2>
            <p class="text-gray-500 max-w-lg mx-auto">Proses pemesanan yang simpel dan cepat tanpa perlu login</p>
        </div>

        <!-- Top Up Steps -->
        <div class="mb-12">
            <h3 class="text-center text-lg font-bold text-gray-700 mb-8 flex items-center justify-center gap-2">
                <i class="fa-solid fa-bolt text-primary-600"></i>Cara Top Up
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                $topupSteps = [
                    ['icon' => 'fa-gamepad', 'color' => 'bg-blue-50 text-blue-600', 'step' => '1', 'title' => 'Pilih Game', 'desc' => 'Pilih game yang ingin kamu top up: ML, FF, atau PUBG'],
                    ['icon' => 'fa-id-card', 'color' => 'bg-purple-50 text-purple-600', 'step' => '2', 'title' => 'Isi ID Akun', 'desc' => 'Masukkan User ID & Server ID, sistem otomatis cek username'],
                    ['icon' => 'fa-gem', 'color' => 'bg-indigo-50 text-indigo-600', 'step' => '3', 'title' => 'Pilih Nominal', 'desc' => 'Pilih jumlah diamond/UC/membership yang diinginkan'],
                    ['icon' => 'fa-credit-card', 'color' => 'bg-green-50 text-green-600', 'step' => '4', 'title' => 'Bayar & Selesai', 'desc' => 'Bayar via VA Bank, Indomaret, Alfamart, atau QRIS'],
                ];
                @endphp
                @foreach($topupSteps as $idx => $s)
                <div class="relative text-center">
                    @if($idx < 3)
                    <div class="hidden lg:block absolute top-8 left-[60%] w-[80%] h-px bg-dashed border-t-2 border-dashed border-gray-200 z-0"></div>
                    @endif
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-16 h-16 {{ $s['color'] }} rounded-2xl flex items-center justify-center mb-4 border-2 border-white shadow-md">
                            <i class="fa-solid {{ $s['icon'] }} text-xl"></i>
                        </div>
                        <span class="w-6 h-6 bg-primary-600 text-white text-xs font-bold rounded-full flex items-center justify-center mb-3">{{ $s['step'] }}</span>
                        <h4 class="font-bold text-gray-900 mb-1.5">{{ $s['title'] }}</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $s['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Joki Steps -->
        <div class="bg-gray-50 rounded-3xl p-8">
            <h3 class="text-center text-lg font-bold text-gray-700 mb-8 flex items-center justify-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i>Cara Pesan Joki
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                $jokiSteps = [
                    ['icon' => 'fa-gamepad', 'color' => 'bg-orange-50 text-orange-600', 'step' => '1', 'title' => 'Pilih Game & Jenis', 'desc' => 'Pilih game dan jenis joki: Rank, Winrate, MMR, dll'],
                    ['icon' => 'fa-layer-group', 'color' => 'bg-amber-50 text-amber-600', 'step' => '2', 'title' => 'Pilih Tier & Paket', 'desc' => 'Pilih tier tujuan dan jumlah yang diinginkan'],
                    ['icon' => 'fa-key', 'color' => 'bg-red-50 text-red-600', 'step' => '3', 'title' => 'Isi Data Akun', 'desc' => 'Input email & password akun game (aman & terenkripsi)'],
                    ['icon' => 'fa-check-circle', 'color' => 'bg-teal-50 text-teal-600', 'step' => '4', 'title' => 'Bayar & Tunggu', 'desc' => 'Bayar & admin kami proses dalam 10 menit pada jam kerja'],
                ];
                @endphp
                @foreach($jokiSteps as $s)
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 {{ $s['color'] }} rounded-2xl flex items-center justify-center mb-4 border-2 border-white shadow-md">
                        <i class="fa-solid {{ $s['icon'] }} text-xl"></i>
                    </div>
                    <span class="w-6 h-6 bg-amber-500 text-white text-xs font-bold rounded-full flex items-center justify-center mb-3">{{ $s['step'] }}</span>
                    <h4 class="font-bold text-gray-900 mb-1.5">{{ $s['title'] }}</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ===== WHY US ===== -->
<section class="py-16 bg-primary-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-white mb-3">Kenapa Pilih Maullstore?</h2>
            <p class="text-primary-200 max-w-lg mx-auto">Kami berkomitmen memberikan layanan terbaik untuk para gamer</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
            $features = [
                ['icon' => 'fa-bolt', 'title' => 'Proses Instan', 'desc' => 'Top up masuk ke akun dalam hitungan menit, otomatis via sistem'],
                ['icon' => 'fa-shield-halved', 'title' => 'Aman & Terpercaya', 'desc' => 'Data akun game dienkripsi. Pembayaran via Midtrans resmi'],
                ['icon' => 'fa-tags', 'title' => 'Harga Terjangkau', 'desc' => 'Harga kompetitif dengan diskon spesial di produk pilihan'],
                ['icon' => 'fa-headset', 'title' => 'Support 24/7', 'desc' => 'Tim kami siap membantu via live chat setiap hari'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-5 text-center hover:bg-white/20 transition-colors">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid {{ $f['icon'] }} text-white text-lg"></i>
                </div>
                <h4 class="font-bold text-white mb-2">{{ $f['title'] }}</h4>
                <p class="text-sm text-primary-200 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-1.5 text-primary-600 text-sm font-semibold mb-3">
                <i class="fa-solid fa-star"></i>Testimoni
            </span>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-3">Kata Pelanggan Kami</h2>
            <p class="text-gray-500">Ribuan gamer sudah percaya pada layanan Maullstore</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $testimonials = [
                ['name' => 'Aldi Pratama', 'game' => 'Top Up ML Diamond', 'rating' => 5, 'text' => 'Top up cepet banget! Baru bayar langsung masuk diamond-nya. Harganya juga murah dari tempat lain. Recommended!', 'avatar' => '👦'],
                ['name' => 'Siti Rahma', 'game' => 'Joki Rank Free Fire', 'rating' => 5, 'text' => 'Joki FF-nya profesional, rank naik sesuai target. Komunikasi admin ramah dan responsif. Pasti repeat order!', 'avatar' => '👧'],
                ['name' => 'Budi Santoso', 'game' => 'Top Up PUBG UC', 'rating' => 5, 'text' => 'Prosesnya mudah banget, gak perlu login segala. Bayar pakai QRIS langsung selesai. Aman terpercaya!', 'avatar' => '🧑'],
            ];
            @endphp
            @foreach($testimonials as $t)
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < $t['rating']; $i++)
                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                    @endfor
                </div>
                <p class="text-gray-600 text-sm leading-relaxed mb-5">"{{ $t['text'] }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center text-xl">{{ $t['avatar'] }}</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $t['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $t['game'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10">
            <div class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i class="fa-solid fa-magnifying-glass text-primary-600 text-2xl"></i>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3">Cek Status Transaksimu</h2>
            <p class="text-gray-500 mb-7">Masukkan nomor invoice, email, atau nomor HP untuk melihat status order</p>
            <a href="/cek-status" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg">
                <i class="fa-solid fa-search"></i>Cek Status Sekarang
            </a>
        </div>
    </div>
</section>

@endsection
