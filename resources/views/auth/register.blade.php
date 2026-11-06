<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Maullstore</title>
    <meta name="description" content="Buat akun Maullstore untuk kemudahan top up, joki, dan riwayat transaksi.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' } }, fontFamily: { sans: ['Inter','sans-serif'] } } }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-primary-950 to-slate-900 flex items-center justify-center p-4 font-sans" x-data="{ showPass: false, showConfirm: false }">

    {{-- Decorative Background --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 bg-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-900/50 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-gamepad text-white text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold text-white">Maull<span class="text-primary-400">store</span></span>
            </a>
            <p class="text-slate-400 text-sm mt-3">Buat akun baru — gratis & cepat</p>
        </div>

        {{-- Card --}}
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
            {{-- Errors --}}
            @if($errors->any())
            <div class="mb-5 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-red-300 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-400 text-xs"></i>{{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <h1 class="text-2xl font-extrabold text-white mb-6">Buat Akun Baru</h1>

            <form action="{{ route('customer.register.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="Nama kamu..."
                            class="w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" name="email" id="reg-email" value="{{ old('email') }}" required
                            placeholder="contoh@email.com"
                            class="w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input :type="showPass ? 'text' : 'password'" name="password" id="reg-password" required
                            placeholder="Min. 8 karakter"
                            class="w-full pl-11 pr-12 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        <button type="button" @click="showPass = !showPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                            <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="reg-password-confirm" required
                            placeholder="Ulangi password"
                            class="w-full pl-11 pr-12 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" id="btn-register"
                    class="w-full py-3.5 bg-primary-600 hover:bg-primary-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary-900/50 hover:shadow-primary-800/50 hover:scale-[1.02] active:scale-100 flex items-center justify-center gap-2 mt-2">
                    <i class="fa-solid fa-user-plus"></i>
                    Buat Akun Sekarang
                </button>
            </form>

            {{-- Divider --}}
            <div class="my-6 flex items-center gap-3">
                <div class="flex-1 h-px bg-white/10"></div>
                <span class="text-xs text-slate-500">atau</span>
                <div class="flex-1 h-px bg-white/10"></div>
            </div>

            {{-- Login Link --}}
            <p class="text-center text-slate-400 text-sm">
                Sudah punya akun?
                <a href="{{ route('customer.login') }}" class="text-primary-400 hover:text-primary-300 font-semibold transition-colors">Masuk di sini</a>
            </p>
        </div>

        {{-- Back to home --}}
        <div class="text-center mt-6">
            <a href="/" class="text-slate-500 hover:text-slate-300 text-sm transition-colors">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
