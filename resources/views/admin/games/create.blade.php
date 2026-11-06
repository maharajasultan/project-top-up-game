@extends('layouts.admin')

@section('title', 'Tambah Game')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.games.index') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Daftar Game</a>
    <h1 class="text-2xl font-bold text-gray-900">Tambah Game Baru</h1>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden max-w-3xl">
    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
        @csrf
        
        <div class="space-y-6">
            <!-- Nama Game -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Game <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all"
                    placeholder="Contoh: Mobile Legends">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Banner -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Banner / Ikon Game</label>
                <input type="file" name="banner" accept="image/*"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-all">
                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, WEBP. Maks: 2MB.</p>
                @error('banner') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                <!-- Fitur -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2">Fitur Tersedia</h3>
                    
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="available_topup" value="1" {{ old('available_topup', true) ? 'checked' : '' }} class="w-5 h-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Top Up</p>
                            <p class="text-xs text-gray-500">Aktifkan fitur top up untuk game ini</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="available_joki" value="1" {{ old('available_joki', true) ? 'checked' : '' }} class="w-5 h-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Joki</p>
                            <p class="text-xs text-gray-500">Aktifkan fitur joki untuk game ini</p>
                        </div>
                    </label>
                </div>

                <!-- Opsi Tambahan -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2">Pengaturan Tambahan</h3>
                    
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="has_server_id" value="1" {{ old('has_server_id') ? 'checked' : '' }} class="w-5 h-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Butuh Server ID</p>
                            <p class="text-xs text-gray-500">Centang jika top up butuh Zone/Server ID</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-green-500 rounded border-gray-300 focus:ring-green-500">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Status Aktif</p>
                            <p class="text-xs text-gray-500">Tampilkan game ini di website</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Sort Order -->
            <div class="pt-4 border-t border-gray-100">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                    class="w-full md:w-1/3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all">
                <p class="text-xs text-gray-500 mt-2">Angka lebih kecil tampil lebih dulu.</p>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan Game
            </button>
            <a href="{{ route('admin.games.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
