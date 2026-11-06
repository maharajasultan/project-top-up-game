<nav class="fixed top-0 left-0 right-0 z-50 bg-white navbar-shadow" x-data="{ mobileOpen: false, scrolled: false, userMenu: false }" @scroll.window="scrolled = window.scrollY > 10" :class="scrolled ? 'navbar-shadow' : ''">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-9 h-9 bg-primary-600 rounded-xl flex items-center justify-center shadow-md group-hover:bg-primary-700 transition-colors">
                    <i class="fa-solid fa-gamepad text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold text-gray-900">
                    Maull<span class="text-primary-600">store</span>
                </span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-1">
                <a href="/" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all {{ request()->is('/') ? 'text-primary-600 bg-primary-50' : '' }}">
                    Beranda
                </a>

                <!-- Top Up Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="/topup" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all flex items-center gap-1 {{ request()->is('topup*') ? 'text-primary-600 bg-primary-50' : '' }}">
                        Top Up
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display:none;">
                        <a href="/topup/mobile-legends" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            Mobile Legends
                        </a>
                        <a href="/topup/free-fire" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            Free Fire
                        </a>
                        <a href="/topup/pubg-mobile" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            PUBG Mobile
                        </a>
                    </div>
                </div>

                <!-- Joki Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="/joki" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all flex items-center gap-1 {{ request()->is('joki*') ? 'text-primary-600 bg-primary-50' : '' }}">
                        Joki
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display:none;">
                        <a href="/joki/mobile-legends" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            Mobile Legends
                        </a>
                        <a href="/joki/free-fire" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            Free Fire
                        </a>
                        <a href="/joki/pubg-mobile" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            PUBG Mobile
                        </a>
                    </div>
                </div>

                <!-- Riwayat Transaksi (hanya tampil jika sudah login) -->
                @auth
                <a href="{{ route('transaction.history') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all {{ request()->is('riwayat-transaksi') ? 'text-primary-600 bg-primary-50' : '' }}">
                    Riwayat Transaksi
                </a>
                @endauth
                <a href="/faq" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all {{ request()->is('faq') ? 'text-primary-600 bg-primary-50' : '' }}">
                    FAQ
                </a>
            </div>

            <!-- Auth Buttons + Hamburger -->
            <div class="flex items-center gap-2">
                @auth
                    {{-- User Dropdown --}}
                    <div class="hidden md:block relative" @click.away="userMenu = false">
                        <button @click="userMenu = !userMenu"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700">
                            <div class="w-7 h-7 bg-primary-100 rounded-lg flex items-center justify-center text-primary-700 font-bold text-xs">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400" :class="userMenu ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="userMenu"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display:none;">
                            <a href="{{ route('transaction.history') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                <i class="fa-solid fa-clock-rotate-left w-4 text-center text-gray-400"></i>
                                Riwayat Transaksi
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}" class="hidden md:flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all border border-gray-200">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i> Login
                    </a>
                    <a href="{{ route('customer.register') }}" class="hidden md:flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition-all shadow-sm">
                        <i class="fa-solid fa-user-plus text-xs"></i> Daftar
                    </a>
                @endauth

                <button @click="mobileOpen = !mobileOpen" class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-white border-t border-gray-100 px-4 py-4 space-y-1" style="display:none;">

        @auth
        {{-- Mobile: Info User --}}
        <div class="flex items-center gap-3 px-4 py-3 bg-primary-50 rounded-xl mb-2">
            <div class="w-9 h-9 bg-primary-100 rounded-xl flex items-center justify-center text-primary-700 font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
        @endauth

        <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
            Beranda
        </a>
        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wide">Top Up</div>
        <a href="/topup/mobile-legends" class="block px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
            Mobile Legends
        </a>
        <a href="/topup/free-fire" class="block px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
            Free Fire
        </a>
        <a href="/topup/pubg-mobile" class="block px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
            PUBG Mobile
        </a>
        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wide">Joki</div>
        <a href="/joki/mobile-legends" class="block px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
            Mobile Legends
        </a>
        <a href="/joki/free-fire" class="block px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
            Free Fire
        </a>
        <a href="/joki/pubg-mobile" class="block px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
            PUBG Mobile
        </a>
        <div class="border-t border-gray-100 pt-3 mt-3 space-y-1">
            <!-- Riwayat Transaksi (hanya tampil jika sudah login) -->
            @auth
            <a href="{{ route('transaction.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                <i class="fa-solid fa-clock-rotate-left w-4 text-center text-gray-400"></i>
                Riwayat Transaksi
            </a>
            @endauth
            <a href="/faq" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                FAQ
            </a>

            @auth
            {{-- Logout Mobile --}}
            <form action="{{ route('customer.logout') }}" method="POST" class="pt-1">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>Logout
                </button>
            </form>
            @else
            {{-- Login / Register Mobile --}}
            <div class="grid grid-cols-2 gap-2 pt-2">
                <a href="{{ route('customer.login') }}" class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-gray-700 border border-gray-200 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i> Login
                </a>
                <a href="{{ route('customer.register') }}" class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                    <i class="fa-solid fa-user-plus text-xs"></i> Daftar
                </a>
            </div>
            @endauth
        </div>
    </div>
</nav>
