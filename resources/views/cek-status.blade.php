@extends('layouts.app')

@section('title', 'Cek Status Transaksi')
@section('meta_description', 'Cek status transaksi Top Up & Joki kamu di Maullstore menggunakan Invoice ID, Email, atau Nomor HP.')

@section('content')
<div class="pt-24 pb-16 min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-magnifying-glass text-primary-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">Cek Status Transaksi</h1>
            <p class="text-gray-500">Masukkan Invoice ID untuk mengecek status order kamu</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8" x-data="{
            searchType: 'invoice',
            searchValue: '',
            searching: false,
            result: null,
            notFound: false,

            async search() {
                if (!this.searchValue.trim()) return;
                this.searching = true;
                this.result = null;
                this.notFound = false;
                
                try {
                    const response = await fetch('/api/cek-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ invoice: this.searchValue })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        const order = data.order;
                        this.result = {
                            invoice: order.invoice_number,
                            type: data.type === 'topup' ? 'Top Up' : 'Jasa Joki',
                            game: order.game?.name || '-',
                            product: data.type === 'topup' ? (order.product?.name || '-') : (order.joki_type?.name || '-'),
                            username: data.type === 'topup' ? order.username_result : order.account_email,
                            status: order.status,
                            amount: data.type === 'topup' ? order.final_price : order.final_total_price,
                            created_at: new Date(order.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) + ' WIB',
                            paid_at: order.paid_at ? new Date(order.paid_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) + ' WIB' : '—',
                            email: data.type === 'topup' ? order.email : order.customer_email,
                        };
                    } else {
                        this.notFound = true;
                    }
                } catch (error) {
                    console.error('Error fetching status:', error);
                    this.notFound = true;
                } finally {
                    this.searching = false;
                }
            },

            statusLabel(s) {
                return { success: 'Sukses', pending: 'Menunggu Pembayaran', failed: 'Gagal', expired: 'Kadaluarsa', waiting_process: 'Menunggu Diproses', on_process: 'Sedang Dikerjakan', completed: 'Selesai' }[s] || s;
            },
            statusColor(s) {
                return { success: 'text-green-700 bg-green-100 border-green-200', completed: 'text-green-700 bg-green-100 border-green-200', pending: 'text-amber-700 bg-amber-100 border-amber-200', waiting_process: 'text-amber-700 bg-amber-100 border-amber-200', on_process: 'text-blue-700 bg-blue-100 border-blue-200', failed: 'text-red-700 bg-red-100 border-red-200', expired: 'text-gray-700 bg-gray-100 border-gray-200' }[s] || 'text-gray-700 bg-gray-100';
            },
            formatRupiah(n) { return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); },
        }">

            <!-- Search Type Tabs -->
            <div class="flex gap-1 p-1 bg-gray-100 rounded-xl mb-5">
                <button class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all bg-white text-primary-700 shadow-sm">
                    <i class="fa-solid fa-receipt mr-1.5"></i>Invoice ID
                </button>
            </div>

            <!-- Input -->
            <div class="flex gap-3 mb-5">
                <input type="text" x-model="searchValue" @keydown.enter="search()"
                    placeholder="Contoh: INV-2026070500123"
                    class="flex-1 px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <button @click="search()" :disabled="!searchValue.trim() || searching"
                    class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-5 py-3 rounded-xl transition-all whitespace-nowrap">
                    <svg class="animate-spin w-4 h-4" x-show="searching" fill="none" viewBox="0 0 24 24" style="display:none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <i class="fa-solid fa-magnifying-glass" x-show="!searching"></i>
                    <span x-text="searching ? 'Mencari...' : 'Cari'"></span>
                </button>
            </div>

            <!-- Not Found -->
            <div x-show="notFound" class="text-center py-8 animate-fade-in" style="display:none">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-face-frown text-gray-400 text-2xl"></i>
                </div>
                <p class="font-semibold text-gray-700 mb-1">Transaksi Tidak Ditemukan</p>
                <p class="text-sm text-gray-400">Coba periksa kembali Invoice ID yang kamu masukkan</p>
            </div>

            <!-- Result -->
            <div x-show="result" class="animate-fade-in space-y-4" style="display:none">
                <!-- Status Card -->
                <div class="border rounded-2xl p-5" :class="{
                    'border-green-200 bg-green-50': result?.status === 'success' || result?.status === 'completed',
                    'border-amber-200 bg-amber-50': result?.status === 'pending' || result?.status === 'waiting_process',
                    'border-blue-200 bg-blue-50': result?.status === 'on_process',
                    'border-red-200 bg-red-50': result?.status === 'failed',
                    'border-gray-200 bg-gray-50': result?.status === 'expired',
                }">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Nomor Invoice</p>
                            <p class="font-bold text-gray-900 text-sm" x-text="result?.invoice"></p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold border" :class="statusColor(result?.status)" x-text="statusLabel(result?.status)"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Jenis Layanan</p>
                            <p class="font-medium text-gray-800" x-text="result?.type"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Game</p>
                            <p class="font-medium text-gray-800" x-text="result?.game"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Produk</p>
                            <p class="font-medium text-gray-800" x-text="result?.product"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Username</p>
                            <p class="font-medium text-gray-800" x-text="result?.username"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Waktu Order</p>
                            <p class="font-medium text-gray-800" x-text="result?.created_at"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Waktu Bayar</p>
                            <p class="font-medium text-gray-800" x-text="result?.paid_at || '—'"></p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t flex items-center justify-between" :class="{
                        'border-green-200': result?.status === 'success' || result?.status === 'completed',
                        'border-amber-200': result?.status === 'pending' || result?.status === 'waiting_process',
                        'border-gray-200': true
                    }">
                        <span class="text-sm text-gray-500">Total Bayar</span>
                        <span class="font-extrabold text-gray-900 text-lg" x-text="formatRupiah(result?.amount ?? 0)"></span>
                    </div>
                </div>

                <!-- Success Icon -->
                <div x-show="result?.status === 'success'" class="flex items-center gap-3 p-4 bg-green-50 rounded-xl border border-green-100" style="display:none">
                    <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                    <div>
                        <p class="font-semibold text-green-800 text-sm">Pembayaran Berhasil!</p>
                        <p class="text-xs text-green-700">Diamond sudah masuk ke akun <span x-text="result?.username"></span>. Cek konfirmasi di email <span x-text="result?.email"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">Ada pertanyaan? <a href="#" class="text-primary-600 font-medium hover:text-primary-700" onclick="document.querySelector('.chat-widget-btn')?.click()">Chat dengan kami</a></p>
        </div>
    </div>
</div>
@endsection
