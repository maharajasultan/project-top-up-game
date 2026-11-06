@extends('layouts.admin')

@section('title', 'Tambah Tipe Joki')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.joki-types.index', ['game_id' => request('game_id')]) }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Daftar Tipe</a>
    <h1 class="text-2xl font-bold text-gray-900">Tambah Tipe Joki Baru</h1>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden max-w-3xl">
    <form action="{{ route('admin.joki-types.store') }}" method="POST" class="p-6 sm:p-8">
        @csrf
        
        <div class="space-y-6">
            <!-- Game -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Game <span class="text-red-500">*</span></label>
                <select name="game_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Pilih Game --</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ old('game_id', request('game_id')) == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
                @error('game_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Tipe Joki -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tipe Joki <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Contoh: Rank Booster / Joki Classic / Joki MCL">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Unit & Min Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Satuan (Unit) <span class="text-red-500">*</span></label>
                    <input type="text" name="unit_name" value="{{ old('unit_name', 'Bintang') }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                        placeholder="Contoh: Bintang / Match / Point">
                    @error('unit_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Minimal Order <span class="text-red-500">*</span></label>
                    <input type="number" name="min_order" value="{{ old('min_order', 3) }}" required min="1"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('min_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Layanan (Opsional)</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Contoh: Layanan joki rank akan dikerjakan maksimal 24 jam...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-6 border-t border-gray-100">
                <!-- Settings -->
                <label class="flex items-center gap-3 cursor-pointer p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                    <input type="checkbox" name="is_flat_price" value="1" {{ old('is_flat_price') ? 'checked' : '' }} class="w-5 h-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Harga Flat</p>
                        <p class="text-xs text-gray-500">Harga per unit sama rata</p>
                    </div>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-green-500 rounded border-gray-300 focus:ring-green-500">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Status Aktif</p>
                        <p class="text-xs text-gray-500">Tampilkan ke publik</p>
                    </div>
                </label>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan Tipe
            </button>
            <a href="{{ route('admin.joki-types.index', ['game_id' => request('game_id')]) }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
