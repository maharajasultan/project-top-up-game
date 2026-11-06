@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Website</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola konfigurasi umum dan integrasi API website Anda.</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @csrf
    
    <!-- General Config -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-globe text-primary-500"></i> Pengaturan Umum
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Website</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'Maullstore') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('site_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp CS</label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-200 bg-gray-100 text-gray-500 text-sm">
                        +62
                    </span>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" placeholder="81234567890" class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-r-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <p class="text-[10px] text-gray-400 mt-1">Gunakan format angka saja tanpa 0 atau +62 di awal. Contoh: 81234567890</p>
                @error('whatsapp_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">URL Instagram</label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" placeholder="https://instagram.com/maullstore" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('instagram_url')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
    
    <!-- Midtrans Config -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-blue-500"></i> Pengaturan Payment Gateway (Midtrans)
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Environment</label>
                <select name="midtrans_is_production" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="0" {{ old('midtrans_is_production', $settings['midtrans_is_production'] ?? '0') == '0' ? 'selected' : '' }}>Sandbox (Testing)</option>
                    <option value="1" {{ old('midtrans_is_production', $settings['midtrans_is_production'] ?? '0') == '1' ? 'selected' : '' }}>Production (Live)</option>
                </select>
                @error('midtrans_is_production')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div x-data="{ showClient: false }">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Client Key</label>
                <div class="relative">
                    <input :type="showClient ? 'text' : 'password'" name="midtrans_client_key" value="{{ old('midtrans_client_key', $settings['midtrans_client_key'] ?? '') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 pr-10">
                    <button type="button" @click="showClient = !showClient" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid" :class="showClient ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('midtrans_client_key')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div x-data="{ showServer: false }">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Server Key</label>
                <div class="relative">
                    <input :type="showServer ? 'text' : 'password'" name="midtrans_server_key" value="{{ old('midtrans_server_key', $settings['midtrans_server_key'] ?? '') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 pr-10">
                    <button type="button" @click="showServer = !showServer" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid" :class="showServer ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('midtrans_server_key')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
    
    <!-- Email/SMTP Config -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-envelope text-red-500"></i> Pengaturan Notifikasi Email (SMTP Gmail)
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mail Host</label>
                <input type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host'] ?? 'smtp.gmail.com') }}" placeholder="smtp.gmail.com" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('mail_host')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mail Port</label>
                <input type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}" placeholder="587" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('mail_port')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address (Username)</label>
                <input type="email" name="mail_username" value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" placeholder="email@gmail.com" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('mail_username')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div x-data="{ showMailPass: false }">
                <label class="block text-sm font-semibold text-gray-700 mb-2">App Password</label>
                <div class="relative">
                    <input :type="showMailPass ? 'text' : 'password'" name="mail_password" value="{{ old('mail_password', $settings['mail_password'] ?? '') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 pr-10">
                    <button type="button" @click="showMailPass = !showMailPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid" :class="showMailPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 mt-1">Gunakan App Password dari Google Account, bukan password email biasa.</p>
                @error('mail_password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Pengirim</label>
                    <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" placeholder="noreply@maullstore.com" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('mail_from_address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pengirim</label>
                    <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? 'Maullstore') }}" placeholder="Maullstore" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('mail_from_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Operational Settings -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-clock text-orange-500"></i> Jam Operasional Joki
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mulai Jam (0-23)</label>
                    <input type="number" min="0" max="23" name="work_start_hour" value="{{ old('work_start_hour', $settings['work_start_hour'] ?? '9') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('work_start_hour')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Selesai Jam (0-23)</label>
                    <input type="number" min="0" max="23" name="work_end_hour" value="{{ old('work_end_hour', $settings['work_end_hour'] ?? '21') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('work_end_hour')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">Digunakan untuk menghitung SLA Respon Joki (10 menit). Di luar jam operasional, peringatan SLA akan dimatikan otomatis.</p>
        </div>
    </div>
    
    <div class="lg:col-span-2 flex justify-end mt-4">
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-md">
            Simpan Pengaturan
        </button>
    </div>
</form>
@endsection
