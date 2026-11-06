@extends('layouts.app')

@section('title', 'FAQ — Pertanyaan Umum')
@section('meta_description', 'Temukan jawaban atas pertanyaan umum seputar Top Up Game dan Jasa Joki di Maullstore.')

@section('content')
<div class="pt-24 pb-16 min-h-screen bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-12">
            <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-circle-question text-primary-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">Pertanyaan Umum (FAQ)</h1>
            <p class="text-gray-500">Temukan jawaban atas pertanyaan yang sering ditanyakan</p>
        </div>

        @php
        $faqs = [
            ['cat' => 'Top Up', 'icon' => 'fa-bolt', 'color' => 'primary',
             'items' => [
                ['q' => 'Berapa lama proses Top Up setelah bayar?', 'a' => 'Proses Top Up bersifat otomatis. Setelah pembayaran dikonfirmasi, diamond/UC akan masuk ke akun kamu dalam hitungan menit (biasanya < 5 menit). Kamu juga akan mendapat notifikasi via email.'],
                ['q' => 'Apakah saya perlu login atau daftar akun?', 'a' => 'Tidak perlu! Kamu bisa langsung Top Up tanpa perlu membuat akun di Maullstore. Cukup masukkan User ID game dan data kontak kamu.'],
                ['q' => 'Bagaimana cara mengecek User ID Mobile Legends?', 'a' => 'Buka game ML → tap foto profil kamu → User ID dan Server ID tertera di bawah nama karakter kamu.'],
                ['q' => 'Metode pembayaran apa saja yang tersedia?', 'a' => 'Kami menerima: Transfer Bank (Virtual Account Mandiri, BNI, BRI), Minimarket (Indomaret & Alfamart), dan QRIS.'],
                ['q' => 'Batas waktu pembayaran berapa lama?', 'a' => 'Kamu memiliki 15 menit untuk menyelesaikan pembayaran setelah order dibuat. Jika melewati batas waktu, order akan otomatis dibatalkan.'],
                ['q' => 'Bagaimana jika saya salah input User ID?', 'a' => 'Sistem kami akan mengecek username secara otomatis sebelum kamu bisa lanjut ke tahap pembayaran. Pastikan username yang muncul sudah benar sebelum melanjutkan.'],
            ]],
            ['cat' => 'Jasa Joki', 'icon' => 'fa-trophy', 'color' => 'amber',
             'items' => [
                ['q' => 'Berapa lama proses joki dimulai?', 'a' => 'Setelah pembayaran berhasil, admin kami akan menghubungi kamu dalam maksimal 10 menit pada jam operasional (08.00–22.00 WIB).'],
                ['q' => 'Apakah data akun game saya aman?', 'a' => 'Ya! Password akun game kamu disimpan dalam format terenkripsi di database kami. Akun hanya diakses untuk keperluan proses joki dan akan dikembalikan sepenuhnya setelah selesai.'],
                ['q' => 'Apa itu V2L dan kenapa harus dimatikan?', 'a' => 'V2L (Verifikasi 2 Langkah) atau 2FA adalah fitur keamanan tambahan. Harus dimatikan sementara agar tim joki dapat login ke akun kamu untuk memproses order. Setelah joki selesai, kamu bisa aktifkan kembali.'],
                ['q' => 'Berapa minimal order joki?', 'a' => 'Minimal order joki ML (Rank, Winrate Classic, MMR Global) adalah 3. Minimal joki FF (Pengaman Rank & Class Squad) adalah 3. Untuk PUBG Mobile sesuai paket tier yang dipilih.'],
                ['q' => 'Apa yang terjadi jika joki gagal?', 'a' => 'Hubungi admin kami via live chat. Kami berkomitmen untuk memberikan layanan terbaik dan akan mengevaluasi setiap kasus secara adil.'],
            ]],
            ['cat' => 'Pembayaran & Refund', 'icon' => 'fa-credit-card', 'color' => 'green',
             'items' => [
                ['q' => 'Apakah ada biaya tambahan?', 'a' => 'Tidak ada biaya tersembunyi. Harga yang tertera sudah final. Beberapa metode pembayaran mungkin memiliki biaya admin dari pihak bank/platform tersebut.'],
                ['q' => 'Bagaimana cara cek status transaksi?', 'a' => 'Kunjungi halaman Cek Status dan masukkan nomor Invoice, email, atau nomor HP yang kamu daftarkan saat order.'],
                ['q' => 'Bagaimana jika pembayaran berhasil tapi top up tidak masuk?', 'a' => 'Hubungi admin kami segera via live chat dengan menyertakan nomor Invoice dan bukti pembayaran. Tim kami akan memproses dalam waktu 1x24 jam kerja.'],
            ]],
        ];
        @endphp

        <div class="space-y-8" x-data="{ openItem: null }">
            @foreach($faqs as $section)
            <div>
                <!-- Section Header -->
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $section['color'] === 'primary' ? 'bg-primary-100' : ($section['color'] === 'amber' ? 'bg-amber-100' : 'bg-green-100') }}">
                        <i class="fa-solid {{ $section['icon'] }} text-sm {{ $section['color'] === 'primary' ? 'text-primary-600' : ($section['color'] === 'amber' ? 'text-amber-600' : 'text-green-600') }}"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $section['cat'] }}</h2>
                </div>

                <div class="space-y-2">
                    @foreach($section['items'] as $idx => $faq)
                    @php $key = $section['cat'].'_'.$idx; @endphp
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:border-primary-200 transition-colors">
                        <button @click="openItem = openItem === '{{ $key }}' ? null : '{{ $key }}'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900 text-sm pr-4">{{ $faq['q'] }}</span>
                            <i class="fa-solid fa-chevron-down text-gray-400 text-xs flex-shrink-0 transition-transform duration-200"
                               :class="openItem === '{{ $key }}' ? 'rotate-180 text-primary-500' : ''"></i>
                        </button>
                        <div x-show="openItem === '{{ $key }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                            class="px-5 pb-4 border-t border-gray-100" style="display:none">
                            <p class="text-sm text-gray-600 leading-relaxed pt-3">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Still have questions? -->
        <div class="mt-12 text-center p-8 bg-primary-50 rounded-3xl border border-primary-100">
            <div class="w-12 h-12 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-headset text-primary-600 text-lg"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Masih ada pertanyaan?</h3>
            <p class="text-gray-500 text-sm mb-5">Tim kami siap membantu kamu via live chat</p>
            <button onclick="document.querySelector('.chat-widget-btn')?.click()" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-all shadow-sm hover:shadow-md">
                <i class="fa-solid fa-comments"></i>Mulai Chat
            </button>
        </div>
    </div>
</div>
@endsection
