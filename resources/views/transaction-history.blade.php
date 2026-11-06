@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('meta_description', 'Lihat riwayat transaksi Top Up dan Joki kamu di Maullstore.')

@section('content')
<div class="pt-24 pb-16 min-h-screen bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-200">
                <i class="fa-solid fa-clock-rotate-left text-2xl text-primary-600"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Riwayat Transaksi</h1>
                <p class="text-gray-500 text-sm">Pantau status pesanan Top Up dan Joki kamu di sini.</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
            @if($transactions->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-receipt text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Transaksi</h3>
                    <p class="text-gray-500 text-sm mb-6">Kamu belum melakukan pembelian apapun. Yuk, mulai transaksi pertamamu!</p>
                    <a href="/" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all">
                        <i class="fa-solid fa-cart-shopping text-sm"></i> Belanja Sekarang
                    </a>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($transactions as $trx)
                        <div class="p-5 sm:p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-gray-100 {{ $trx->transaction_type == 'topup' ? 'bg-primary-50 text-primary-600' : 'bg-amber-50 text-amber-600' }}">
                                        @if($trx->game && $trx->game->banner_url)
                                            <img src="{{ $trx->game->banner_url }}" alt="{{ $trx->game->name }}" class="w-full h-full object-cover rounded-xl">
                                        @else
                                            <i class="fa-solid {{ $trx->transaction_type == 'topup' ? 'fa-bolt' : 'fa-trophy' }} text-lg"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-bold px-2 py-0.5 rounded-md uppercase tracking-wider {{ $trx->transaction_type == 'topup' ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $trx->transaction_type }}
                                            </span>
                                            <span class="text-xs text-gray-400 font-medium">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <h3 class="font-bold text-gray-900 leading-tight mb-1">
                                            <a href="{{ route('transaction.detail', $trx->invoice_number) }}" class="hover:text-primary-600 hover:underline">
                                                {{ $trx->title }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $trx->game->name ?? 'Unknown Game' }} &bull; Inv: <span class="font-mono text-gray-600">{{ $trx->invoice_number }}</span></p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2 pl-16 sm:pl-0 border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0">
                                    <p class="font-extrabold text-gray-900">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                                    
                                    @if($trx->status == 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                            <i class="fa-solid fa-clock text-[10px]"></i> Menunggu Pembayaran
                                        </span>
                                    @elseif($trx->status == 'success' || $trx->status == 'waiting_process')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <i class="fa-solid fa-check text-[10px]"></i> Berhasil
                                        </span>
                                    @elseif($trx->status == 'processing')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            <i class="fa-solid fa-spinner animate-spin text-[10px]"></i> Diproses
                                        </span>
                                    @elseif($trx->status == 'expired')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                            <i class="fa-solid fa-calendar-xmark text-[10px]"></i> Kadaluarsa
                                        </span>
                                    @elseif($trx->status == 'failed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Gagal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                            <i class="fa-solid fa-question text-[10px]"></i> {{ ucfirst($trx->status) }}
                                        </span>
                                    @endif
                                    
                                    <a href="{{ route('transaction.detail', $trx->invoice_number) }}" class="mt-1 text-[10px] font-semibold text-primary-600 hover:text-primary-700 underline">Lihat Detail &rarr;</a>
                                </div>
                            </div>
                            
                            {{-- Tombol Lanjut Bayar jika pending & snap_token masih ada --}}
                            @if($trx->status == 'pending' && $trx->snap_token)
                            <div class="mt-4 pl-16 flex flex-col gap-1.5">
                                <button 
                                    onclick="lanjutBayar('{{ $trx->snap_token }}', '{{ $trx->invoice_number }}')"
                                    class="inline-flex items-center gap-2 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 active:bg-primary-800 px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md w-fit">
                                    <i class="fa-solid fa-credit-card text-xs"></i>
                                    Lanjut Bayar Sekarang
                                </button>
                                @if($trx->expired_at)
                                <div class="mt-2.5 text-xs text-amber-600 flex items-center gap-2">
                                    <i class="fa-solid fa-clock text-[10px] animate-pulse"></i>
                                    <span class="font-semibold uppercase tracking-wider text-[9px]">Sisa Waktu:</span>
                                    <div class="flex items-center gap-1 font-mono font-bold text-amber-800 history-countdown" data-expire="{{ $trx->expired_at->toIso8601String() }}" data-invoice="{{ $trx->invoice_number }}">
                                        <span class="bg-amber-100 px-1.5 py-0.5 rounded shadow-sm history-h">00</span>:
                                        <span class="bg-amber-100 px-1.5 py-0.5 rounded shadow-sm history-m">00</span>:
                                        <span class="bg-amber-100 px-1.5 py-0.5 rounded shadow-sm history-s">00</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @elseif($trx->status == 'pending' && !$trx->snap_token)
                            <div class="mt-4 pl-16">
                                <p class="text-xs text-red-500 flex items-center gap-1 mb-1">
                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                    Token pembayaran tidak tersedia.
                                </p>
                                <a href="{{ $trx->transaction_type == 'topup' ? '/topup' : '/joki' }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                                    Buat Transaksi Baru <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
    </div>
</div>

{{-- Modal: Pembayaran Berhasil --}}
<div id="modalBerhasil" class="fixed inset-0 z-[200] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="flex items-center justify-center min-h-screen p-4 relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 animate-fade-in">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-gray-500 text-sm">Transaksi kamu sedang diproses oleh tim kami. Status akan diperbarui otomatis.</p>
                <p id="invoiceBerhasil" class="text-xs font-mono text-gray-400 mt-2 bg-gray-50 rounded-lg px-3 py-1 inline-block"></p>
            </div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('modalBerhasil').classList.add('hidden')" 
                    class="flex-1 py-3 px-4 rounded-xl text-sm font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors">
                    Tutup
                </button>
                <button id="btnLihatDetail" onclick="window.location.reload()" 
                    class="flex-1 py-3 px-4 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                    Lihat Detail <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Menunggu Pembayaran --}}
<div id="modalPending" class="fixed inset-0 z-[200] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="flex items-center justify-center min-h-screen p-4 relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 animate-fade-in">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-clock text-amber-500 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Menunggu Pembayaran</h3>
                <p class="text-gray-500 text-sm">Pembayaran kamu sedang menunggu konfirmasi. Selesaikan pembayaran sesuai instruksi yang diberikan.</p>
                <p class="text-xs text-amber-600 mt-3 bg-amber-50 border border-amber-100 rounded-xl px-4 py-2">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                    Status akan diperbarui otomatis setelah pembayaran terkonfirmasi.
                </p>
            </div>
            <button onclick="document.getElementById('modalPending').classList.add('hidden')" 
                class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 transition-colors">
                Mengerti, Saya Akan Selesaikan Pembayaran
            </button>
        </div>
    </div>
</div>

{{-- Modal: Pembayaran Gagal --}}
<div id="modalGagal" class="fixed inset-0 z-[200] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="flex items-center justify-center min-h-screen p-4 relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 animate-fade-in">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-circle-xmark text-red-500 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Gagal</h3>
                <p class="text-gray-500 text-sm">Pembayaran tidak dapat diproses. Silakan coba lagi atau hubungi admin.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('modalGagal').classList.add('hidden')" 
                    class="flex-1 py-3 px-4 rounded-xl text-sm font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors">
                    Tutup
                </button>
                <a href="/" 
                    class="flex-1 py-3 px-4 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition-colors text-center">
                    Hubungi Admin
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * Membuka kembali Midtrans Snap popup untuk melanjutkan pembayaran yang pending.
     * Menggunakan snap_token yang sudah tersimpan di database, bukan membuat order baru.
     */
    function lanjutBayar(snapToken, invoice) {
        if (!snapToken) {
            alert('Token pembayaran tidak tersedia. Silakan buat transaksi baru.');
            return;
        }

        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                // Pembayaran berhasil → sync status ke API Midtrans
                fetch('/api/payment/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ invoice: invoice })
                }).then(res => res.json()).then(data => {
                    document.getElementById('invoiceBerhasil').textContent = 'Invoice: ' + invoice;
                    document.getElementById('modalBerhasil').classList.remove('hidden');
                    
                    document.getElementById('btnLihatDetail').onclick = function() {
                        window.location.href = '/transaksi/' + invoice;
                    };
                });
            },
            onPending: function(result) {
                // Masih menunggu (contoh: transfer bank) → tampilkan modal pending
                document.getElementById('modalPending').classList.remove('hidden');
            },
            onError: function(result) {
                // Gagal → tampilkan modal gagal
                document.getElementById('modalGagal').classList.remove('hidden');
            },
            onClose: function() {
                // User menutup popup tanpa aksi apapun — tidak perlu redirect
                console.log('Popup pembayaran ditutup');
            }
        });
    }

    // Auto-sync status untuk transaksi yang masih pending saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const pendingInvoices = @json($transactions->where('status', 'pending')->pluck('invoice_number')->values());
        
        if (pendingInvoices.length > 0) {
            let hasChanges = false;
            let promises = pendingInvoices.map(invoice => {
                return fetch('/api/payment/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ invoice: invoice })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && ['success', 'waiting_process', 'completed'].includes(data.status)) {
                        hasChanges = true;
                    }
                })
                .catch(err => console.error(err));
            });
            
            Promise.all(promises).then(() => {
                if (hasChanges) {
                    // Jika ada status yang berubah sukses, refresh halaman untuk menampilkan status terbaru
                    window.location.reload();
                }
            });
        }
        
        // Countdown Logic untuk Riwayat Transaksi
        const countdowns = document.querySelectorAll('.history-countdown');
        if (countdowns.length > 0) {
            setInterval(() => {
                const now = new Date().getTime();
                
                countdowns.forEach(el => {
                    if (el.dataset.expiredTriggered === 'true') return;
                    
                    const expireTime = new Date(el.getAttribute('data-expire')).getTime();
                    const distance = expireTime - now;
                    
                    if (distance < 0) {
                        el.querySelector('.history-h').textContent = '00';
                        el.querySelector('.history-m').textContent = '00';
                        el.querySelector('.history-s').textContent = '00';
                        
                        el.dataset.expiredTriggered = 'true';
                        fetch('/api/payment/sync', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ invoice: el.getAttribute('data-invoice') })
                        }).then(() => window.location.reload());
                    } else {
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        
                        el.querySelector('.history-h').textContent = hours.toString().padStart(2, '0');
                        el.querySelector('.history-m').textContent = minutes.toString().padStart(2, '0');
                        el.querySelector('.history-s').textContent = seconds.toString().padStart(2, '0');
                    }
                });
            }, 1000);
        }
    });
</script>
@endpush
