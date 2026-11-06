@extends('layouts.app')

@section('title', 'Joki ' . $game->name)
@section('meta_description', 'Jasa Joki ' . $game->name . ' profesional dan terpercaya di Maullstore.')

@section('content')

@php
    // Map joki types + tiers ke format Alpine-compatible
    $jokiTypes = $game->jokiTypes->map(fn($type) => [
        'id'            => $type->id,
        'name'          => $type->name,
        'desc'          => $type->description ?? 'Layanan joki profesional',
        'unit_name'     => $type->unit_name ?? 'Bintang',
        'min_order'     => $type->min_order ?? 1,
        'is_flat_price' => (bool) $type->is_flat_price,
        'tiers'         => $type->tiers->map(fn($tier) => [
            'id'             => $tier->id,
            'tier_name'      => $tier->tier_name,
            'price_per_unit' => (int) $tier->price_per_unit,
        ])->values()->toArray(),
    ])->values();

    // Login methods
    $loginMethods = $game->jokiLoginMethods->pluck('method_name');
@endphp

<div class="pt-24 pb-16 min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{
        step: 1,
        selectedType: null,
        selectedTier: null,
        orderQty: 0,
        loginMethod: '',
        accountEmail: '',
        accountPassword: '',
        showPassword: false,
        v2lDisabled: null,
        email: '',
        phone: '',
        paymentMethod: '',
        currentInvoice: '',
        showTestimonialModal: false,
        showPendingModal: false,
        showErrorModal: false,
        testimonialText: '',
        testimonialRating: 5,
        paying: false,

        // Data dari database
        jokiTypes: {{ $jokiTypes->toJson() }},
        loginMethods: {{ $loginMethods->toJson() }},

        async paymentSuccess() {
            if (!this.canPay()) return;
            this.paying = true;
            try {
                const response = await fetch('/joki/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        game_slug:     '{{ $game->slug }}',
                        joki_type_id:  this.selectedType ? this.selectedType.id : null,
                        joki_tier_id:  this.selectedTier ? this.selectedTier.id : null,
                        rank_awal:     this.selectedTier ? this.selectedTier.tier_name : null,
                        qty:           this.orderQty,
                        login_method:  this.loginMethod,
                        email_akun:    this.accountEmail,
                        password_akun: this.accountPassword,
                        price:         this.getTotalPrice(),
                        email:         this.email,
                        phone:         this.phone,
                        payment_method: this.paymentMethod
                    })
                });
                const data = await response.json();
                if (data.success && data.snap_token) {
                    this.currentInvoice = data.invoice;
                    window.snap.pay(data.snap_token, {
                        onSuccess: (result) => {
                            // Pembayaran berhasil → tampilkan modal testimonial
                            this.showTestimonialModal = true;
                        },
                        onPending: (result) => {
                            // Pembayaran pending → tampilkan modal informatif
                            // Status di admin akan otomatis terupdate via Midtrans webhook
                            this.showPendingModal = true;
                        },
                        onError: (result) => {
                            // Pembayaran gagal → tampilkan modal error
                            this.showErrorModal = true;
                        },
                        onClose: () => {
                            // User menutup popup tanpa aksi — tidak perlu redirect
                            console.log('Popup pembayaran ditutup');
                        }
                    });
                } else {
                    alert(data.message || 'Gagal memproses pembayaran');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan pada server');
            } finally {
                this.paying = false;
            }
        },
        submitTestimonial() {
            // Setelah kirim ulasan, arahkan ke halaman detail transaksi
            this.showTestimonialModal = false;
            window.location.href = this.currentInvoice ? '/transaksi/' + this.currentInvoice : '/riwayat-transaksi';
        },
        skipTestimonial() {
            // Skip ulasan, langsung ke halaman detail transaksi
            this.showTestimonialModal = false;
            window.location.href = this.currentInvoice ? '/transaksi/' + this.currentInvoice : '/riwayat-transaksi';
        },

        selectType(t) {
            this.selectedType = t;
            this.orderQty     = t.min_order;
            // Auto-pilih tier pertama jika hanya 1 tier
            this.selectedTier = (t.tiers.length === 1) ? t.tiers[0] : null;
        },
        selectTier(tier) { this.selectedTier = tier; },

        incrementQty() { this.orderQty++; },
        decrementQty() {
            if (this.selectedType && this.orderQty > this.selectedType.min_order) {
                this.orderQty--;
            }
        },

        canGoStep2() {
            if (!this.selectedType) return false;
            if (this.selectedType.tiers.length > 1 && !this.selectedTier) return false;
            return this.orderQty >= this.selectedType.min_order;
        },
        canGoStep3()  { return this.loginMethod && this.accountEmail && this.accountPassword && this.v2lDisabled === true; },
        canPay()      { return this.email && this.phone && this.paymentMethod; },

        getTotalPrice() {
            if (!this.selectedTier) return 0;
            return this.orderQty * this.selectedTier.price_per_unit;
        },
        formatRupiah(n) { return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); },
    }">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8 flex-wrap">
            <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="/joki" class="hover:text-primary-600 transition-colors">Joki</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $game->name }}</span>
        </nav>

        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center overflow-hidden shadow-sm border border-gray-200 flex-shrink-0">
                <img src="{{ $game->banner_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Joki {{ $game->name }}</h1>
                <p class="text-gray-500 text-sm">Pilih jenis, tier, jumlah, dan isi data akun</p>
            </div>
        </div>

        <!-- Step Progress -->
        <div class="flex items-center mb-10">
            @php $steps = [['n'=>1,'label'=>'Jenis & Tier'],['n'=>2,'label'=>'Data Akun'],['n'=>3,'label'=>'Kontak & Bayar']]; @endphp
            @foreach($steps as $s)
            <div class="flex items-center flex-1">
                <div class="flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all"
                        :class="{ 'bg-amber-500 text-white shadow-md': step === {{ $s['n'] }}, 'bg-green-500 text-white': step > {{ $s['n'] }}, 'bg-gray-100 text-gray-400': step < {{ $s['n'] }} }">
                        <span x-show="step <= {{ $s['n'] }}">{{ $s['n'] }}</span>
                        <i class="fa-solid fa-check text-xs" x-show="step > {{ $s['n'] }}" style="display:none"></i>
                    </div>
                    <span class="text-xs mt-1 font-medium hidden sm:block"
                        :class="{ 'text-amber-600': step === {{ $s['n'] }}, 'text-green-600': step > {{ $s['n'] }}, 'text-gray-400': step < {{ $s['n'] }} }">{{ $s['label'] }}</span>
                </div>
                @if(!$loop->last)
                <div class="flex-1 h-px mx-2" :class="step > {{ $s['n'] }} ? 'bg-green-400' : 'bg-gray-200'"></div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- ===== STEP 1: Pilih Jenis & Tier ===== -->
        <div x-show="step === 1" class="space-y-5 animate-fade-in">

            <!-- 1a: Pilih Jenis Layanan dari DB -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <div class="w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-list text-amber-600 text-xs"></i>
                        </div>
                        Pilih Jenis Layanan
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    <template x-for="type in jokiTypes" :key="type.id">
                        <div @click="selectType(type)"
                            class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition-colors"
                            :class="selectedType?.id === type.id ? 'bg-amber-50' : ''">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-all"
                                :class="selectedType?.id === type.id ? 'bg-amber-100' : 'bg-gray-100'">
                                <i class="fa-solid fa-trophy text-sm"
                                    :class="selectedType?.id === type.id ? 'text-amber-600' : 'text-gray-400'"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm" x-text="type.name"></p>
                                <p class="text-xs text-gray-500">
                                    <span x-text="type.desc"></span>
                                    &bull;
                                    <template x-if="type.tiers.length === 1">
                                        <span class="text-amber-600 font-medium" x-text="'Rp ' + type.tiers[0].price_per_unit.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, '.') + '/' + type.unit_name"></span>
                                    </template>
                                    <template x-if="type.tiers.length > 1">
                                        <span class="text-amber-600 font-medium" x-text="type.tiers.length + ' pilihan tier'"></span>
                                    </template>
                                </p>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                :class="selectedType?.id === type.id ? 'border-amber-500 bg-amber-500' : 'border-gray-300'">
                                <i class="fa-solid fa-check text-white text-xs" x-show="selectedType?.id === type.id"></i>
                            </div>
                        </div>
                    </template>
                    <template x-if="jokiTypes.length === 0">
                        <div class="p-8 text-center text-gray-400">
                            <i class="fa-solid fa-box-open text-3xl mb-2 opacity-30"></i>
                            <p class="text-sm">Belum ada layanan tersedia</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- 1b: Pilih Tier (hanya jika type punya > 1 tier) -->
            <div x-show="selectedType && selectedType.tiers.length > 1" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" style="display:none">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <div class="w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-amber-600 text-xs"></i>
                        </div>
                        Pilih Tier / Rank
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <template x-for="tier in (selectedType ? selectedType.tiers : [])" :key="tier.id">
                            <button @click="selectTier(tier)"
                                class="py-3 px-3 rounded-xl text-sm font-semibold border-2 transition-all text-left"
                                :class="selectedTier?.id === tier.id ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 text-gray-600 hover:border-amber-300 hover:text-amber-600'">
                                <p x-text="tier.tier_name" class="font-bold text-sm leading-tight"></p>
                                <p x-text="'Rp ' + tier.price_per_unit.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, '.') + '/' + (selectedType ? selectedType.unit_name : '')" class="text-xs mt-0.5 opacity-70"></p>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- 1c: Jumlah Order (muncul setelah type & tier dipilih) -->
            <div x-show="selectedType && selectedTier" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" style="display:none">
                <div class="border-t border-gray-100 p-5 bg-amber-50/40">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-800 mb-1">Jumlah <span x-text="selectedType?.unit_name"></span></p>
                            <p class="text-xs text-gray-500">Minimal order: <span x-text="selectedType?.min_order"></span> <span x-text="selectedType?.unit_name"></span></p>
                        </div>
                        <div class="flex items-center gap-0 bg-white rounded-xl border border-gray-300 overflow-hidden">
                            <button @click="decrementQty()" type="button"
                                :disabled="orderQty <= (selectedType ? selectedType.min_order : 1)"
                                class="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 disabled:opacity-30 disabled:cursor-not-allowed border-r border-gray-200">
                                <i class="fa-solid fa-minus text-sm"></i>
                            </button>
                            <div class="w-16 h-12 flex items-center justify-center">
                                <span class="text-xl font-extrabold text-gray-900" x-text="orderQty"></span>
                            </div>
                            <button @click="incrementQty()" type="button"
                                class="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 border-l border-gray-200">
                                <i class="fa-solid fa-plus text-sm"></i>
                            </button>
                        </div>
                        <div class="text-right sm:text-left">
                            <p class="text-xs text-gray-500 mb-0.5">Total Harga:</p>
                            <p class="text-xl font-extrabold text-amber-600" x-text="formatRupiah(getTotalPrice())"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button @click="step = 2" :disabled="!canGoStep2()"
                    class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-6 py-3 rounded-xl transition-all shadow-sm hover:shadow-md">
                    Lanjut Isi Data Akun <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>

        <!-- ===== STEP 2: Data Akun Game ===== -->
        <div x-show="step === 2" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-5 animate-fade-in" style="display:none">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <div class="w-7 h-7 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-key text-amber-600 text-xs"></i>
                </div>
                Data Login Akun Game
            </h2>

            <!-- Ringkasan pilihan -->
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                <div>
                    <p class="text-xs text-amber-600 font-medium mb-0.5">Jenis Joki</p>
                    <p class="font-bold text-gray-800" x-text="selectedType?.name"></p>
                </div>
                <div x-show="selectedTier">
                    <p class="text-xs text-amber-600 font-medium mb-0.5">Tier</p>
                    <p class="font-bold text-gray-800" x-text="selectedTier?.tier_name || '-'"></p>
                </div>
                <div>
                    <p class="text-xs text-amber-600 font-medium mb-0.5">Jumlah Order</p>
                    <p class="font-bold text-gray-800"><span x-text="orderQty"></span> <span x-text="selectedType?.unit_name"></span></p>
                </div>
                <div>
                    <p class="text-xs text-amber-600 font-medium mb-0.5">Total Harga</p>
                    <p class="font-bold text-gray-800" x-text="formatRupiah(getTotalPrice())"></p>
                </div>
            </div>

            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-start gap-3">
                <i class="fa-solid fa-shield-halved text-amber-500 mt-0.5"></i>
                <p class="text-sm text-gray-600">Data login kamu <strong>dienkripsi</strong> dan hanya digunakan untuk proses joki. Akun akan dikembalikan setelah selesai.</p>
            </div>

            <!-- Metode Login dari DB -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Metode Login Akun <span class="text-red-500">*</span></label>
                <select x-model="loginMethod" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all bg-white">
                    <option value="">— Pilih Metode Login —</option>
                    <template x-for="method in loginMethods" :key="method">
                        <option :value="method" x-text="method"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email / Username Akun Game <span class="text-red-500">*</span></label>
                <input type="text" x-model="accountEmail" placeholder="Email atau username akun game..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Akun Game <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" x-model="accountPassword" placeholder="Password akun game..." class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all">
                    <button @click="showPassword = !showPassword" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                        <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- V2L -->
            <div class="p-4 border rounded-xl" :class="v2lDisabled === true ? 'bg-green-50 border-green-200' : v2lDisabled === false ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200'">
                <p class="text-sm font-semibold text-gray-800 mb-3">
                    <i class="fa-solid fa-shield-halved mr-1.5 text-amber-500"></i>
                    Verifikasi 2 Langkah (V2L/2FA) sudah dimatikan? <span class="text-red-500">*</span>
                </p>
                <div class="flex gap-3">
                    <button @click="v2lDisabled = true" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold transition-all border-2"
                        :class="v2lDisabled === true ? 'bg-green-600 border-green-600 text-white' : 'bg-white border-gray-200 text-gray-700 hover:border-green-400'">
                        <i class="fa-solid fa-check"></i>Ya, sudah
                    </button>
                    <button @click="v2lDisabled = false" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold transition-all border-2"
                        :class="v2lDisabled === false ? 'bg-red-500 border-red-500 text-white' : 'bg-white border-gray-200 text-gray-700 hover:border-red-400'">
                        <i class="fa-solid fa-xmark"></i>Belum
                    </button>
                </div>
                <div x-show="v2lDisabled === false" class="mt-3 p-3 bg-red-100 rounded-lg" style="display:none">
                    <p class="text-sm text-red-700">⚠️ Matikan V2L terlebih dahulu. Joki tidak bisa diproses jika V2L masih aktif.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <button @click="step = 1" class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>Kembali
                </button>
                <button @click="step = 3" :disabled="!canGoStep3()" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-6 py-3 rounded-xl transition-all shadow-sm hover:shadow-md">
                    Lanjut Isi Kontak <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>

        <!-- ===== STEP 3: Kontak, Metode Bayar & Ringkasan ===== -->
        <div x-show="step === 3" class="space-y-5 animate-fade-in" style="display:none">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                    <div class="w-7 h-7 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-envelope text-amber-600 text-xs"></i>
                    </div>
                    Data Kontak
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Aktif <span class="text-red-500">*</span></label>
                        <input type="email" x-model="email" placeholder="contoh@email.com" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all">
                        <p class="text-xs text-gray-400 mt-1.5">Notifikasi status joki akan dikirim ke email ini</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                        <input type="tel" x-model="phone" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all">
                    </div>
                </div>
            </div>

            <!-- Ringkasan Order -->
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Ringkasan Order Joki</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Game</span>
                        <span class="font-medium text-gray-900">{{ $game->name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jenis Joki</span>
                        <span class="font-medium text-gray-900" x-text="selectedType?.name"></span>
                    </div>
                    <div class="flex justify-between text-sm" x-show="selectedTier">
                        <span class="text-gray-500">Tier</span>
                        <span class="font-medium text-gray-900" x-text="selectedTier?.tier_name || '-'"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jumlah</span>
                        <span class="font-medium text-gray-900"><span x-text="orderQty"></span> <span x-text="selectedType?.unit_name"></span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Metode Login</span>
                        <span class="font-medium text-gray-900" x-text="loginMethod || '-'"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">V2L</span>
                        <span class="text-green-600 font-medium">✓ Sudah dimatikan</span>
                    </div>
                    <div class="h-px bg-gray-200 my-2"></div>
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-900">Total Bayar</span>
                        <span class="font-extrabold text-amber-600 text-lg" x-text="formatRupiah(getTotalPrice())"></span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-xl text-xs text-blue-700">
                    <i class="fa-solid fa-headset mr-1.5"></i>
                    Setelah pembayaran berhasil, admin kami menghubungi dalam <strong>10 menit</strong> pada jam operasional.
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                    <div class="w-7 h-7 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-amber-600 text-xs"></i>
                    </div>
                    Pilih Metode Pembayaran
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                    $paymentMethods = [
                        ['key' => 'qris',      'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/QRIS_logo.svg/200px-QRIS_logo.svg.png', 'label' => 'QRIS'],
                        ['key' => 'gopay',     'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/200px-Gopay_logo.svg.png', 'label' => 'GoPay'],
                        ['key' => 'shopeepay', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/ShopeePay.svg/200px-ShopeePay.svg.png', 'label' => 'ShopeePay'],
                        ['key' => 'bca_va',    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/200px-Bank_Central_Asia.svg.png', 'label' => 'BCA VA'],
                        ['key' => 'echannel',  'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png', 'label' => 'Mandiri VA'],
                        ['key' => 'bni_va',    'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/200px-BNI_logo.svg.png', 'label' => 'BNI VA'],
                        ['key' => 'indomaret', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Indomaret_logo.svg/200px-Indomaret_logo.svg.png', 'label' => 'Indomaret'],
                        ['key' => 'alfamart',  'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Alfamart.svg/200px-Alfamart.svg.png', 'label' => 'Alfamart'],
                    ];
                    @endphp
                    @foreach($paymentMethods as $pm)
                    <div @click="paymentMethod = '{{ $pm['key'] }}'"
                        :class="paymentMethod === '{{ $pm['key'] }}' ? 'border-amber-600 bg-amber-50 ring-2 ring-amber-100' : 'border-gray-200 hover:border-amber-300 hover:bg-gray-50'"
                        class="border rounded-xl p-3 cursor-pointer transition-all flex flex-col items-center justify-center gap-2 min-h-[90px] relative">
                        <div class="w-full flex items-center justify-center h-10">
                            <img src="{{ $pm['logo'] }}" alt="{{ $pm['label'] }}" class="max-h-10 max-w-full object-contain" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <i class="fa-solid fa-credit-card text-gray-400 text-xl" style="display:none"></i>
                        </div>
                        <p class="font-bold text-gray-700 text-xs text-center">{{ $pm['label'] }}</p>
                        <i class="fa-solid fa-check-circle text-amber-600 absolute top-2 right-2 text-sm" x-show="paymentMethod === '{{ $pm['key'] }}'"></i>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between mt-5">
                <button @click="step = 2" class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>Kembali
                </button>
                @auth
                <button @click="paymentSuccess()" :disabled="!canPay() || paying" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg">
                    <svg class="animate-spin w-4 h-4" x-show="paying" fill="none" viewBox="0 0 24 24" style="display:none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <i class="fa-solid fa-lock text-sm" x-show="!paying"></i>
                    <span x-text="paying ? 'Memproses...' : 'Bayar Sekarang'"></span>
                </button>
                @else
                <a href="{{ route('customer.login') }}?redirect={{ url()->current() }}" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-right-to-bracket text-sm"></i>
                    Login untuk Membeli
                </a>
                @endauth
            </div>
        </div>

        <!-- Testimonial Modal -->
        <div x-show="showTestimonialModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display:none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="skipTestimonial()" class="relative bg-white rounded-3xl shadow-2xl sm:max-w-lg w-full p-8"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-check text-amber-500 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
                        <p class="text-gray-500 text-sm">Pesanan joki kamu sedang dikerjakan. Berikan penilaian atas layanan kami.</p>
                    </div>
                    <div class="flex justify-center gap-2 mb-4">
                        <template x-for="i in 5">
                            <button @click="testimonialRating = i" class="text-3xl transition-transform hover:scale-110" :class="i <= testimonialRating ? 'text-amber-400' : 'text-gray-200'">
                                <i class="fa-solid fa-star"></i>
                            </button>
                        </template>
                    </div>
                    <textarea x-model="testimonialText" rows="3" placeholder="Bagaimana pengalaman kamu transaksi di Maullstore?"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 resize-none"></textarea>
                    <div class="mt-6 flex gap-3">
                        <button @click="skipTestimonial()" class="flex-1 py-3 px-4 rounded-xl text-sm font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200">Nanti Saja</button>
                        <button @click="submitTestimonial()" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold text-white bg-amber-500 hover:bg-amber-600">Kirim Ulasan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Menunggu Pembayaran -->
        <div x-show="showPendingModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display:none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-3xl shadow-2xl sm:max-w-lg w-full p-8"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-clock text-amber-500 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Menunggu Pembayaran</h3>
                        <p class="text-gray-500 text-sm">Pembayaran kamu sedang menunggu konfirmasi. Selesaikan sesuai instruksi yang diberikan.</p>
                        <p class="text-xs text-amber-700 mt-3 bg-amber-50 border border-amber-100 rounded-xl px-4 py-2">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                            Status di admin akan otomatis terupdate setelah pembayaran dikonfirmasi oleh sistem.
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="showPendingModal = false" class="flex-1 py-3 px-4 rounded-xl text-sm font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200">Tutup</button>
                        <a :href="currentInvoice ? '/transaksi/' + currentInvoice : '/riwayat-transaksi'" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Pembayaran Gagal -->
        <div x-show="showErrorModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display:none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-3xl shadow-2xl sm:max-w-lg w-full p-8"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-circle-xmark text-red-500 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Gagal</h3>
                        <p class="text-gray-500 text-sm">Pembayaran tidak dapat diproses. Silakan coba lagi atau pilih metode pembayaran lain.</p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="showErrorModal = false" class="flex-1 py-3 px-4 rounded-xl text-sm font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200">Coba Lagi</button>
                        <a :href="currentInvoice ? '/transaksi/' + currentInvoice : '/riwayat-transaksi'" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 text-center flex items-center justify-center gap-2">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
