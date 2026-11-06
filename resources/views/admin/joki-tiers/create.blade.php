@extends('layouts.admin')

@section('title', 'Tambah Tier Joki')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.joki-tiers.index', ['joki_type_id' => request('joki_type_id')]) }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Daftar Tier</a>
    <h1 class="text-2xl font-bold text-gray-900">Tambah Tier Baru</h1>
    <p class="text-sm text-gray-500 mt-1">Tipe Joki: {{ $jokiType->name }} ({{ $jokiType->game->name }})</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden max-w-3xl">
    <form action="{{ route('admin.joki-tiers.store') }}" method="POST" class="p-6 sm:p-8">
        @csrf
        <input type="hidden" name="joki_type_id" value="{{ $jokiType->id }}">
        
        <div class="space-y-6">
            <!-- Nama Tier -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tier/Paket <span class="text-red-500">*</span></label>
                <input type="text" name="tier_name" value="{{ old('tier_name') }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Contoh: Epic ke Legend / Bintang 1-50">
                @error('tier_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Harga & Min Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga per {{ $jokiType->unit_name }} (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_unit" value="{{ old('price_per_unit') }}" required min="0" step="1"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                        placeholder="Contoh: 5000">
                    @if($jokiType->is_flat_price)
                        <p class="text-xs text-blue-500 mt-1">Info: Tipe joki ini menggunakan harga flat. Harga akan disamakan untuk semua tier oleh sistem jika diperlukan.</p>
                    @endif
                    @error('price_per_unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Minimal Order <span class="text-red-500">*</span></label>
                    <input type="number" name="min_order" value="{{ old('min_order', $jokiType->min_order) }}" required min="1"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <p class="text-xs text-gray-500 mt-1">Default mengikuti pengaturan tipe joki.</p>
                    @error('min_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Contoh: Pengerjaan cepat khusus tier Epic">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                <!-- Settings -->
                <label class="flex items-center gap-3 cursor-pointer p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors h-full">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-green-500 rounded border-gray-300 focus:ring-green-500">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Status Aktif</p>
                        <p class="text-xs text-gray-500">Tampilkan tier ini</p>
                    </div>
                </label>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan Tampil (Sort Order) <span class="text-red-500">*</span></label>
                    <input type="number" name="tier_order" value="{{ old('tier_order', 1) }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <p class="text-xs text-gray-500 mt-1">Untuk mengurutkan dari rank terendah ke tertinggi.</p>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan Tier
            </button>
            <a href="{{ route('admin.joki-tiers.index', ['joki_type_id' => request('joki_type_id')]) }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
