<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Maullstore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AlpineJS for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-900 overflow-hidden" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen w-full">
        <!-- Sidebar -->
        <aside class="flex flex-col w-64 bg-white border-r border-gray-200 h-full transition-all duration-300 z-20 fixed md:relative"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-extrabold text-primary-600 flex items-center gap-2">
                    <i class="fa-solid fa-gamepad"></i> Maullstore
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-4">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
                </a>

                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Transaksi</p>
                <a href="{{ route('admin.orders-topup.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.orders-topup.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-bolt w-5 text-center"></i> Top Up
                </a>
                <a href="{{ route('admin.orders-joki.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.orders-joki.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-trophy w-5 text-center"></i> Joki
                </a>
                
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Master Data</p>
                <a href="{{ route('admin.games.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.games.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-gamepad w-5 text-center"></i> Game
                </a>
                <a href="{{ route('admin.topup-products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.topup-products.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-gem w-5 text-center"></i> Produk Top Up
                </a>
                <a href="{{ route('admin.joki-types.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.joki-types.*') || request()->routeIs('admin.joki-tiers.*') || request()->routeIs('admin.joki-login-methods.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-list w-5 text-center"></i> Paket Joki
                </a>
                
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Komunikasi & Laporan</p>
                <a href="{{ route('admin.chat.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.chat.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-headset w-5 text-center"></i> Live Chat
                    @php
                        $unreadChat = \App\Models\ChatConversation::where('status', 'open')->whereHas('messages', function($q) {
                            $q->where('sender_type', 'customer')->where('is_read', false);
                        })->count();
                    @endphp
                    @if($unreadChat > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadChat }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Laporan Transaksi
                </a>

                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Pengaturan</p>
                <a href="{{ route('admin.admin-users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.admin-users.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center"></i> Admin User
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-users w-5 text-center"></i> Kelola Customer
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-gear w-5 text-center"></i> Setting Web
                </a>
            </div>
            
            <div class="p-4 border-t border-gray-100">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 font-semibold rounded-xl text-sm transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-10 md:hidden" style="display:none;"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative">
            <!-- Navbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-10">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="fa-regular fa-bell text-xl"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-800 leading-none">{{ Auth::guard('admin')->user()->name }}</p>
                            <p class="text-xs text-gray-500 mt-1 uppercase">{{ str_replace('_', ' ', Auth::guard('admin')->user()->role) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-primary-100 border border-primary-200 flex items-center justify-center text-primary-700 font-bold">
                            {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                <div class="bg-green-50 text-green-700 text-sm p-4 rounded-xl mb-6 flex gap-3 items-center border border-green-200 shadow-sm">
                    <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 text-red-700 text-sm p-4 rounded-xl mb-6 flex gap-3 items-center border border-red-200 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-lg"></i>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
