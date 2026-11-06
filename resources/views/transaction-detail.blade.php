@extends('layouts.app')

@section('title', 'Detail Transaksi - ' . $order->invoice_number)
@section('meta_description', 'Detail transaksi ' . strtoupper($orderType) . ' dengan invoice ' . $order->invoice_number . ' di Maullstore.')

@section('content')
<div class="pt-24 pb-16 min-h-screen bg-slate-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
            <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="/riwayat-transaksi" class="hover:text-primary-600 transition-colors">Riwayat Transaksi</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-700 font-medium font-mono">{{ $order->invoice_number }}</span>
        </nav>

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="/riwayat-transaksi" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-arrow-left text-gray-500"></i>
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900">Detail Transaksi</h1>
                    <p class="text-sm text-gray-500 font-mono">{{ $order->invoice_number }}</p>
                </div>
            </div>

            {{-- Status Badge --}}
            @php
                $statusMap = [
                    'pending'         => ['bg-amber-100 text-amber-700 border-amber-200',  'fa-clock',          'Menunggu Pembayaran'],
                    'pending_payment' => ['bg-amber-100 text-amber-700 border-amber-200',  'fa-clock',          'Menunggu Pembayaran'],
                    'success'         => ['bg-green-100 text-green-700 border-green-200',  'fa-circle-check',   'Pembayaran Berhasil'],
                    'waiting_process' => ['bg-blue-100 text-blue-700 border-blue-200',     'fa-hourglass-half', 'Menunggu Diproses'],
                    'on_process'      => ['bg-purple-100 text-purple-700 border-purple-200','fa-spinner fa-spin','Sedang Dikerjakan'],
                    'completed'       => ['bg-green-100 text-green-700 border-green-200',  'fa-circle-check',   'Selesai'],
                    'expired'         => ['bg-gray-100 text-gray-600 border-gray-200',     'fa-calendar-xmark', 'Kadaluarsa'],
                    'failed'          => ['bg-red-100 text-red-700 border-red-200',        'fa-circle-xmark',   'Gagal'],
                ];
                [$statusCls, $statusIcon, $statusLabel] = $statusMap[$order->status] ?? ['bg-gray-100 text-gray-600 border-gray-200', 'fa-question', ucfirst($order->status)];
            @endphp
            <span id="statusBadge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold border {{ $statusCls }}">
                <i class="fa-solid {{ $statusIcon }} text-xs"></i>
                <span id="statusText">{{ $statusLabel }}</span>
            </span>
        </div>

        <div class="space-y-4">

            {{-- Card: Info Transaksi --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $orderType === 'topup' ? 'bg-primary-100' : 'bg-amber-100' }}">
                        <i class="fa-solid {{ $orderType === 'topup' ? 'fa-bolt text-primary-600' : 'fa-trophy text-amber-600' }} text-sm"></i>
                    </div>
                    <h2 class="font-bold text-gray-900">Info {{ $orderType === 'topup' ? 'Top Up' : 'Joki' }}</h2>
                </div>

                <div class="divide-y divide-gray-50">
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Game</span>
                        <div class="flex items-center gap-2">
                            @if($order->game && $order->game->banner_url)
                            <img src="{{ $order->game->banner_url }}" alt="{{ $order->game->name }}" class="w-6 h-6 rounded-md object-cover">
                            @endif
                            <span class="text-sm font-bold text-gray-900">{{ $order->game->name ?? '-' }}</span>
                        </div>
                    </div>

                    @if($orderType === 'topup')
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Produk</span>
                        <span class="text-sm font-bold text-gray-900">{{ $order->product->name ?? '-' }}</span>
                    </div>
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">ID Game</span>
                        <span class="text-sm font-mono font-bold text-gray-900">{{ $order->user_game_id }}</span>
                    </div>
                    @if($order->server_id)
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Server ID</span>
                        <span class="text-sm font-mono font-bold text-gray-900">{{ $order->server_id }}</span>
                    </div>
                    @endif
                    @if($order->username_result)
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Username</span>
                        <span class="text-sm font-bold text-gray-900">{{ $order->username_result }}</span>
                    </div>
                    @endif
                    @else
                    {{-- Joki --}}
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Tipe Joki</span>
                        <span class="text-sm font-bold text-gray-900">{{ $order->jokiType->name ?? '-' }}</span>
                    </div>
                    @if($order->jokiTier)
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Tier / Rank</span>
                        <span class="text-sm font-bold text-gray-900">{{ $order->jokiTier->tier_name }}</span>
                    </div>
                    @endif
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Jumlah</span>
                        <span class="text-sm font-bold text-gray-900">{{ $order->qty }} {{ $order->jokiType->unit_name ?? 'unit' }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Card: Info Pembayaran --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-green-600 text-sm"></i>
                    </div>
                    <h2 class="font-bold text-gray-900">Pembayaran</h2>
                </div>

                <div class="divide-y divide-gray-50">
                    @if($orderType === 'topup')
                        @if($order->original_price && $order->original_price != $order->final_price)
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-sm text-gray-500">Harga Normal</span>
                            <span class="text-sm text-gray-400 line-through">Rp {{ number_format($order->original_price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($order->discount_amount > 0)
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-sm text-gray-500">Diskon</span>
                            <span class="text-sm font-semibold text-green-600">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-800">Total Bayar</span>
                            <span class="text-lg font-extrabold text-primary-600">Rp {{ number_format($order->final_price, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-800">Total Bayar</span>
                            <span class="text-lg font-extrabold text-primary-600">Rp {{ number_format($order->final_total_price, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if($order->payment_channel)
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Metode Bayar</span>
                        <span class="text-sm font-bold text-gray-900 uppercase">{{ $order->payment_channel }}</span>
                    </div>
                    @endif

                    @if($order->paid_at)
                    <div class="px-6 py-3.5 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Tanggal Bayar</span>
                        <span id="paidAtText" class="text-sm font-bold text-gray-900">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                    </div>
                    @else
                    <div class="px-6 py-3.5 flex justify-between items-center" id="paidAtRow" style="{{ $order->paid_at ? '' : 'display:none' }}">
                        <span class="text-sm text-gray-500">Tanggal Bayar</span>
                        <span id="paidAtText" class="text-sm font-bold text-gray-900">-</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Card: Timeline Status --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-timeline text-blue-600 text-sm"></i>
                    </div>
                    <h2 class="font-bold text-gray-900">Riwayat Status</h2>
                </div>
                <div class="px-6 py-5">
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-px bg-gray-200"></div>
                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0 z-10">
                                    <i class="fa-solid fa-plus text-primary-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Order Dibuat</p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i:s') }}</p>
                                </div>
                            </div>

                            @if($order->paid_at)
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 z-10">
                                    <i class="fa-solid fa-check text-green-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Pembayaran Diterima</p>
                                    <p class="text-xs text-gray-400">{{ $order->paid_at->format('d M Y, H:i:s') }}</p>
                                    @if($order->payment_channel)
                                    <span class="text-xs text-gray-500 uppercase bg-gray-100 px-2 py-0.5 rounded-md">{{ $order->payment_channel }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($orderType === 'joki' && in_array($order->status, ['on_process', 'completed']))
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 z-10">
                                    <i class="fa-solid fa-gamepad text-purple-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Joki Sedang Dikerjakan</p>
                                    <p class="text-xs text-gray-400">Tim kami sedang memproses pesanan</p>
                                </div>
                            </div>
                            @endif

                            @if(in_array($order->status, ['success', 'completed']))
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 z-10">
                                    <i class="fa-solid fa-circle-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-green-700">{{ $orderType === 'topup' ? 'Top Up Berhasil!' : 'Joki Selesai!' }}</p>
                                    <p class="text-xs text-gray-400">Transaksi telah selesai diproses</p>
                                </div>
                            </div>
                            @endif

                            @if($order->status === 'expired')
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0 z-10">
                                    <i class="fa-solid fa-calendar-xmark text-gray-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-600">Kadaluarsa</p>
                                    <p class="text-xs text-gray-400">Waktu pembayaran telah habis</p>
                                </div>
                            </div>
                            @endif

                            @if($order->status === 'failed')
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 z-10">
                                    <i class="fa-solid fa-circle-xmark text-red-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-red-600">Pembayaran Gagal</p>
                                    <p class="text-xs text-gray-400">Transaksi tidak berhasil diproses</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Lanjut Bayar (jika masih pending) --}}
            @if(in_array($order->status, ['pending', 'pending_payment']) && $order->snap_token)
            <div class="bg-white border border-amber-200 rounded-3xl p-8 shadow-xl shadow-amber-100/50 mb-8 relative overflow-hidden text-center">
                <!-- Background Decoration -->
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-amber-50 rounded-full mix-blend-multiply filter blur-2xl opacity-70"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-orange-50 rounded-full mix-blend-multiply filter blur-2xl opacity-70"></div>
                
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <i class="fa-solid fa-clock text-amber-500 text-4xl animate-pulse"></i>
                    </div>
                    
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Selesaikan Pembayaran</h3>
                    <p class="text-gray-500 text-sm mb-6 max-w-md mx-auto">Waktu kamu terbatas! Segera selesaikan pembayaran agar pesanan bisa diproses.</p>
                    
                    @if($order->expired_at)
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 inline-block mb-8">
                        <p class="text-[10px] text-amber-600 font-bold uppercase tracking-widest mb-1">Batas Waktu Pembayaran</p>
                        <p class="font-mono text-xl font-black text-amber-800">
                            {{ $order->expired_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    @endif
                    
                    <button
                        onclick="lanjutBayar('{{ $order->snap_token }}', '{{ $order->invoice_number }}')"
                        class="w-full sm:w-auto sm:min-w-[300px] flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-extrabold py-4 px-8 rounded-2xl transition-all shadow-md hover:shadow-lg text-lg mx-auto transform hover:-translate-y-1">
                        <i class="fa-solid fa-credit-card"></i>
                        Bayar Sekarang
                    </button>
                </div>
            </div>
            @endif

            {{-- Tombol Cek Status --}}
            @if(in_array($order->status, ['pending', 'pending_payment', 'waiting_process']))
            <div class="text-center">
                <button id="btnCekStatus" onclick="cekStatusManual('{{ $order->invoice_number }}')"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 bg-white border border-gray-200 hover:border-primary-300 px-5 py-2.5 rounded-xl transition-all shadow-sm">
                    <i class="fa-solid fa-rotate-right" id="iconCekStatus"></i>
                    Cek & Perbarui Status
                </button>
            </div>
            @endif

            {{-- Info Kontak --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-4">
                <p class="text-sm text-gray-500 text-center">
                    Ada pertanyaan? <a href="/" class="text-primary-600 font-semibold hover:underline">Hubungi Admin via Chat</a>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Modal Berhasil --}}
<div id="modalBerhasil" class="fixed inset-0 z-[200] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="flex items-center justify-center min-h-screen p-4 relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 animate-fade-in">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-gray-500 text-sm">Status transaksi kamu sudah diperbarui.</p>
            </div>
            <button onclick="window.location.reload()"
                class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                <i class="fa-solid fa-rotate-right mr-1"></i> Refresh Halaman
            </button>
        </div>
    </div>
</div>

{{-- Modal Pending --}}
<div id="modalPending" class="fixed inset-0 z-[200] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="flex items-center justify-center min-h-screen p-4 relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 animate-fade-in">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-clock text-amber-500 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Menunggu Konfirmasi</h3>
                <p class="text-gray-500 text-sm">Selesaikan pembayaran sesuai instruksi. Status akan diperbarui otomatis.</p>
            </div>
            <button onclick="document.getElementById('modalPending').classList.add('hidden')"
                class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 transition-colors">
                Mengerti
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * Lanjutkan pembayaran dengan snap_token yang tersimpan
     */
    function lanjutBayar(snapToken, invoice) {
        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                // Langsung sync status ke Midtrans
                syncStatus(invoice, function(data) {
                    document.getElementById('modalBerhasil').classList.remove('hidden');
                });
            },
            onPending: function(result) {
                document.getElementById('modalPending').classList.remove('hidden');
            },
            onError: function(result) {
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                console.log('Popup ditutup');
            }
        });
    }

    /**
     * Cek & sinkronisasi status dari Midtrans API secara manual
     */
    function cekStatusManual(invoice) {
        const btn = document.getElementById('btnCekStatus');
        const icon = document.getElementById('iconCekStatus');

        if (btn) {
            btn.disabled = true;
            btn.textContent = ' Mengecek...';
            if (icon) {
                btn.prepend(icon);
                icon.classList.add('animate-spin');
            }
        }

        syncStatus(invoice, function(data) {
            if (btn) {
                btn.disabled = false;
                if (icon) icon.classList.remove('animate-spin');
                btn.innerHTML = '<i class="fa-solid fa-rotate-right" id="iconCekStatus"></i> Cek & Perbarui Status';
            }

            const successStatuses = ['success', 'waiting_process', 'completed'];
            if (data.success && successStatuses.includes(data.status)) {
                document.getElementById('modalBerhasil').classList.remove('hidden');
            } else if (data.success) {
                alert('Status terkini: ' + (data.status || 'Belum ada pembaruan'));
            } else {
                alert('Gagal mengecek status. Coba lagi dalam beberapa saat.');
            }
        });
    }

    /**
     * Hit endpoint sync ke server untuk cek Midtrans API
     */
    function syncStatus(invoice, callback) {
        fetch('/api/payment/sync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ invoice: invoice })
        })
        .then(res => res.json())
        .then(data => {
            if (callback) callback(data);
        })
        .catch(err => {
            console.error('Sync error:', err);
            if (callback) callback({ success: false });
        });
    }

    // Auto-Sync saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const currentStatus = "{{ $order->status }}";
        if (currentStatus === 'pending' || currentStatus === 'pending_payment') {
            const invoiceStr = "{{ $order->invoice_number }}";
            syncStatus(invoiceStr, function(data) {
                const updatedStatuses = ['success', 'waiting_process', 'completed', 'failed', 'expired'];
                if (data.success && updatedStatuses.includes(data.status)) {
                    // Jika ada perubahan status ke selesai/gagal, reload halaman
                    window.location.reload();
                }
            });
        }
    });
</script>
@endpush
