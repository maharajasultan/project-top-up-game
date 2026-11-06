@extends('layouts.app')

@section('title', 'Top Up ' . $game->name)
@section('meta_description', 'Top Up ' . $game->name . ' mudah dan cepat di Maullstore.')

@section('content')

@php
    // Map products from DB to Alpine-compatible format
    $allProducts = $game->topupProducts->map(fn($p) => [
        'id'             => $p->id,
        'name'           => $p->name,
        'price'          => (int) $p->final_price,
        'original_price' => $p->is_on_discount ? (int) $p->price : null,
        'discount'       => $p->is_on_discount ? (int) $p->discount_value : null,
        'popular'        => (bool) $p->is_popular,
        'type'           => $p->type ?? 'diamond',
    ]);

    // Produk utama (diamond/uc) vs spesial (pass/membership)
    $mainProducts    = $allProducts->filter(fn($p) => !in_array($p['type'], ['pass','membership']))->values();
    $specialProducts = $allProducts->filter(fn($p)  => in_array($p['type'], ['pass','membership']))->values();

    // Label mata uang
    $currency = $game->slug === 'pubg-mobile' ? 'UC' : 'Diamond';
@endphp

<div class="pt-24 pb-16 min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{
        step: 1,
        userId: '',
        serverId: '',
        username: '',
        checkingUser: false,
        userChecked: false,
        userError: '',
        selectedProduct: null,
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

        async paymentSuccess() {
            if (!this.canPay()) return;
            this.paying = true;
            try {
                const response = await fetch('/topup/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        game_slug:      '{{ $game->slug }}',
                        product_id:     this.selectedProduct.id,
                        price:          this.selectedProduct.price,
                        user_id:        this.userId,
                        server_id:      this.serverId,
                        username:       this.username,
                        email:          this.email,
                        phone:          this.phone,
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
                            // Pembayaran pending (transfer bank, dll) → tampilkan modal pending
                            // Status di admin akan otomatis terupdate via Midtrans webhook
                            this.showPendingModal = true;
                        },
                        onError: (result) => {
                            // Pembayaran gagal → tampilkan modal error
                            // Status di admin akan otomatis terupdate via Midtrans webhook
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
        checkUsername() {
            if (!this.userId) return;
            this.checkingUser = true;
            this.userError    = '';
            this.userChecked  = false;
            this.username     = '';
            setTimeout(() => {
                if (this.userId.length > 3) {
                    this.username    = 'ProGamer_' + this.userId.slice(-3);
                    this.userChecked = true;
                } else {
                    this.userError = 'User ID tidak ditemukan. Periksa kembali ID kamu.';
                }
                this.checkingUser = false;
            }, 1500);
        },
        selectProduct(p)    { this.selectedProduct = p; },
        canGoToStep2()      { return this.userChecked && this.username !== ''; },
        canGoToStep3()      { return this.selectedProduct !== null; },
        canPay()            { return this.email !== '' && this.phone !== '' && this.paymentMethod !== ''; },
        formatRupiah(n)     { return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); },
    }">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8 flex-wrap">
            <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="/topup" class="hover:text-primary-600 transition-colors">Top Up</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $game->name }}</span>
        </nav>

        <!-- Game Header -->
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center overflow-hidden shadow-sm border border-gray-200 flex-shrink-0">
                <img src="{{ $game->banner_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Top Up {{ $game->name }}</h1>
                <p class="text-gray-500 text-sm">Isi data, pilih nominal, dan bayar. Proses instan!</p>
            </div>
        </div>

        <!-- Step Progress -->
        <div class="flex items-center mb-10">
            @php $steps = [['n'=>1,'label'=>'Akun Game'],['n'=>2,'label'=>'Pilih Nominal'],['n'=>3,'label'=>'Kontak & Bayar']]; @endphp
            @foreach($steps as $s)
            <div class="flex items-center flex-1">
                <div class="flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all"
                        :class="{
                            'bg-primary-600 text-white shadow-md': step === {{ $s['n'] }},
                            'bg-green-500 text-white': step > {{ $s['n'] }},
                            'bg-gray-100 text-gray-400': step < {{ $s['n'] }}
                        }">
                        <span x-show="step <= {{ $s['n'] }}">{{ $s['n'] }}</span>
                        <i class="fa-solid fa-check text-xs" x-show="step > {{ $s['n'] }}" style="display:none"></i>
                    </div>
                    <span class="text-xs mt-1 font-medium hidden sm:block"
                        :class="{
                            'text-primary-600': step === {{ $s['n'] }},
                            'text-green-600': step > {{ $s['n'] }},
                            'text-gray-400': step < {{ $s['n'] }}
                        }">{{ $s['label'] }}</span>
                </div>
                @if(!$loop->last)
                <div class="flex-1 h-px mx-2" :class="step > {{ $s['n'] }} ? 'bg-green-400' : 'bg-gray-200'"></div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- STEP 1: Akun Game -->
        <div x-show="step === 1" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 animate-fade-in">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <div class="w-7 h-7 bg-primary-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-user text-primary-600 text-xs"></i>
                </div>
                Data Akun Game
            </h2>

            <div class="space-y-4">
                <div class="{{ $game->has_server_id ? 'grid grid-cols-1 sm:grid-cols-2 gap-4' : '' }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">User ID <span class="text-red-500">*</span></label>
                        <input type="text" x-model="userId" @input="userChecked = false; username = ''; userError = ''" placeholder="Masukkan User ID..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                    @if($game->has_server_id)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Server ID <span class="text-red-500">*</span> <span class="text-xs text-gray-400">(khusus ML)</span></label>
                        <input type="text" x-model="serverId" placeholder="Masukkan Server ID..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                    @endif
                </div>

                <button @click="checkUsername()" :disabled="!userId{{ $game->has_server_id ? ' || !serverId' : '' }} || checkingUser" class="flex items-center gap-2 bg-gray-900 hover:bg-gray-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all">
                    <i class="fa-solid fa-magnifying-glass text-xs" x-show="!checkingUser"></i>
                    <svg class="animate-spin w-4 h-4" x-show="checkingUser" style="display:none" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="checkingUser ? 'Mengecek...' : 'Cek Username'"></span>
                </button>

                <div x-show="userChecked && !userError" class="flex items-center gap-3 p-4 bg-green-50 rounded-xl border border-green-200 animate-fade-in" style="display:none">
                    <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-check text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-green-600 font-medium">Username ditemukan</p>
                        <p class="text-base font-bold text-green-800" x-text="username"></p>
                    </div>
                </div>
                <div x-show="userError" class="flex items-center gap-3 p-4 bg-red-50 rounded-xl border border-red-200 animate-fade-in" style="display:none">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                    <p class="text-sm text-red-600" x-text="userError"></p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end">
                <button @click="step = 2" :disabled="!canGoToStep2()" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-6 py-3 rounded-xl transition-all shadow-sm hover:shadow-md">
                    Lanjut Pilih Nominal <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: Pilih Produk -->
        <div x-show="step === 2" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 animate-fade-in" style="display:none">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <div class="w-7 h-7 bg-primary-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-gem text-primary-600 text-xs"></i>
                </div>
                Pilih Nominal {{ $currency }}
            </h2>

            <!-- User confirmed -->
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-6 border border-gray-100">
                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-user text-primary-600 text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400">Username terverifikasi</p>
                    <p class="text-sm font-bold text-gray-800 truncate" x-text="username"></p>
                </div>
                <button @click="step = 1" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Ubah</button>
            </div>

            {{-- Main Products (Diamond / UC) --}}
            @if($mainProducts->count() > 0)
            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-3">{{ $currency }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($mainProducts as $p)
                    <div @click="selectProduct({{ json_encode($p) }})"
                        class="product-card relative border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-primary-300 hover:bg-primary-50/50 transition-all"
                        :class="selectedProduct?.id === {{ $p['id'] }} ? 'selected' : ''">
                        @if($p['popular'])
                        <div class="absolute -top-2 left-3">
                            <span class="bg-primary-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">Terpopuler</span>
                        </div>
                        @endif
                        @if($p['discount'])
                        <div class="absolute -top-2 right-3">
                            <span class="badge-discount text-white text-xs font-bold px-2 py-0.5 rounded-full">-{{ $p['discount'] }}%</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2 mb-2 {{ ($p['popular'] || $p['discount']) ? 'mt-4' : '' }}">
                            <p class="text-sm font-bold text-gray-900">{{ $p['name'] }}</p>
                        </div>
                        @if($p['original_price'])
                        <p class="text-xs text-gray-400 line-through">Rp {{ number_format($p['original_price'], 0, ',', '.') }}</p>
                        @endif
                        <p class="text-base font-extrabold text-primary-600">Rp {{ number_format($p['price'], 0, ',', '.') }}</p>
                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                            :class="selectedProduct?.id === {{ $p['id'] }} ? 'border-primary-600 bg-primary-600' : 'border-gray-300 bg-white'">
                            <i class="fa-solid fa-check text-white text-xs" x-show="selectedProduct?.id === {{ $p['id'] }}"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Special Products (Pass / Membership) --}}
            @if($specialProducts->count() > 0)
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-3">Membership &amp; Pass</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($specialProducts as $p)
                    <div @click="selectProduct({{ json_encode($p) }})"
                        class="product-card relative border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-primary-300 hover:bg-primary-50/50 transition-all"
                        :class="selectedProduct?.id === {{ $p['id'] }} ? 'selected' : ''">
                        @if($p['popular'])
                        <div class="absolute -top-2 left-3">
                            <span class="bg-primary-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">Terpopuler</span>
                        </div>
                        @endif
                        @if($p['discount'])
                        <div class="absolute -top-2 right-3">
                            <span class="badge-discount text-white text-xs font-bold px-2 py-0.5 rounded-full">-{{ $p['discount'] }}%</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2 mb-2 {{ ($p['popular'] || $p['discount']) ? 'mt-4' : '' }}">
                            <p class="text-sm font-bold text-gray-900">{{ $p['name'] }}</p>
                        </div>
                        @if($p['original_price'])
                        <p class="text-xs text-gray-400 line-through">Rp {{ number_format($p['original_price'], 0, ',', '.') }}</p>
                        @endif
                        <p class="text-base font-extrabold text-primary-600">Rp {{ number_format($p['price'], 0, ',', '.') }}</p>
                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                            :class="selectedProduct?.id === {{ $p['id'] }} ? 'border-primary-600 bg-primary-600' : 'border-gray-300 bg-white'">
                            <i class="fa-solid fa-check text-white text-xs" x-show="selectedProduct?.id === {{ $p['id'] }}"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($allProducts->isEmpty())
            <div class="text-center py-12 text-gray-400">
                <i class="fa-solid fa-box-open text-4xl mb-3 opacity-30"></i>
                <p>Belum ada produk tersedia</p>
            </div>
            @endif

            <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between">
                <button @click="step = 1" class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>Kembali
                </button>
                <button @click="step = 3" :disabled="!canGoToStep3()" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-6 py-3 rounded-xl transition-all shadow-sm hover:shadow-md">
                    Lanjut Isi Kontak <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: Kontak, Metode Bayar & Ringkasan -->
        <div x-show="step === 3" class="space-y-5 animate-fade-in" style="display:none">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <div class="w-7 h-7 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-envelope text-primary-600 text-xs"></i>
                    </div>
                    Data Kontak
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Aktif <span class="text-red-500">*</span></label>
                        <input type="email" x-model="email" placeholder="contoh@email.com" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        <p class="text-xs text-gray-400 mt-1.5">Invoice &amp; konfirmasi pembayaran akan dikirim ke email ini</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                        <input type="tel" x-model="phone" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Ringkasan Order</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Game</span>
                        <span class="font-medium text-gray-900">{{ $game->name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Username</span>
                        <span class="font-medium text-gray-900" x-text="username"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Produk</span>
                        <span class="font-medium text-gray-900" x-text="selectedProduct?.name"></span>
                    </div>
                    <template x-if="selectedProduct?.original_price">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Harga Normal</span>
                            <span class="text-gray-400 line-through" x-text="'Rp ' + selectedProduct?.original_price?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')"></span>
                        </div>
                    </template>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Hemat</span>
                        <span class="text-green-600 font-medium" x-show="selectedProduct?.discount" x-text="'- ' + selectedProduct?.discount + '%'"></span>
                        <span class="text-gray-400" x-show="!selectedProduct?.discount">-</span>
                    </div>
                    <div class="h-px bg-gray-200 my-2"></div>
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-900">Total Bayar</span>
                        <span class="font-extrabold text-primary-600 text-lg" x-text="formatRupiah(selectedProduct?.price ?? 0)"></span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-amber-50 border border-amber-100 rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-clock text-amber-500 text-sm"></i>
                    <p class="text-xs text-amber-700">Selesaikan pembayaran dalam <strong>15 menit</strong> setelah order dibuat</p>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                    <div class="w-7 h-7 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-primary-600 text-xs"></i>
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
                        :class="paymentMethod === '{{ $pm['key'] }}' ? 'border-primary-600 bg-primary-50 ring-2 ring-primary-100' : 'border-gray-200 hover:border-primary-300 hover:bg-gray-50'"
                        class="border rounded-xl p-3 cursor-pointer transition-all flex flex-col items-center justify-center gap-2 min-h-[90px] relative">
                        <div class="w-full flex items-center justify-center h-10">
                            <img src="{{ $pm['logo'] }}" alt="{{ $pm['label'] }}" class="max-h-10 max-w-full object-contain" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <i class="fa-solid fa-credit-card text-gray-400 text-xl" style="display:none"></i>
                        </div>
                        <p class="font-bold text-gray-700 text-xs text-center">{{ $pm['label'] }}</p>
                        <i class="fa-solid fa-check-circle text-primary-600 absolute top-2 right-2 text-sm" x-show="paymentMethod === '{{ $pm['key'] }}'"></i>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button @click="step = 2" class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>Kembali
                </button>
                @auth
                <button @click="paymentSuccess()" :disabled="!canPay() || paying" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg">
                    <svg class="animate-spin w-4 h-4" x-show="paying" fill="none" viewBox="0 0 24 24" style="display:none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <i class="fa-solid fa-lock text-sm" x-show="!paying"></i>
                    <span x-text="paying ? 'Memproses...' : 'Bayar Sekarang'"></span>
                </button>
                @else
                <a href="{{ route('customer.login') }}?redirect={{ url()->current() }}" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg">
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
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-check text-green-500 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
                        <p class="text-gray-500 text-sm">Transaksi kamu sedang diproses. Mohon berikan penilaian.</p>
                    </div>
                    <div class="flex justify-center gap-2 mb-4">
                        <template x-for="i in 5">
                            <button @click="testimonialRating = i" class="text-3xl transition-transform hover:scale-110" :class="i <= testimonialRating ? 'text-amber-400' : 'text-gray-200'">
                                <i class="fa-solid fa-star"></i>
                            </button>
                        </template>
                    </div>
                    <textarea x-model="testimonialText" rows="3" placeholder="Bagaimana pengalaman kamu transaksi di Maullstore?"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
                    <div class="mt-6 flex gap-3">
                        <button @click="skipTestimonial()" class="flex-1 py-3 px-4 rounded-xl text-sm font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200">Nanti Saja</button>
                        <button @click="submitTestimonial()" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700">Kirim Ulasan</button>
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
