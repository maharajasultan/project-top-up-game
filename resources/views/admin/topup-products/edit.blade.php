@extends('layouts.admin')

@section('title', 'Edit Produk Top Up')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.topup-products.index', ['game_id' => $product->game_id]) }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Daftar Produk</a>
    <h1 class="text-2xl font-bold text-gray-900">Edit Produk: {{ $product->name }}</h1>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden max-w-4xl">
    <form action="{{ route('admin.topup-products.update', $product) }}" method="POST" class="p-6 sm:p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Game -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Game <span class="text-red-500">*</span></label>
                <select name="game_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ old('game_id', $product->game_id) == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
                @error('game_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Tipe Produk -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Produk <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="diamond" {{ old('type', $product->type) == 'diamond' ? 'selected' : '' }}>Diamond</option>
                    <option value="uc" {{ old('type', $product->type) == 'uc' ? 'selected' : '' }}>UC</option>
                    <option value="membership" {{ old('type', $product->type) == 'membership' ? 'selected' : '' }}>Membership / Starlight</option>
                    <option value="pass" {{ old('type', $product->type) == 'pass' ? 'selected' : '' }}>Pass (Royal Pass, dll)</option>
                </select>
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Produk -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Harga Modal -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Modal (Rp)</label>
                <input type="number" name="modal_price" value="{{ old('modal_price', $product->modal_price) }}" min="0" step="1"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <p class="text-xs text-gray-500 mt-1">Hanya untuk pencatatan profit.</p>
                @error('modal_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Harga Jual -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Jual Normal (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="1"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-6 border-t border-gray-100">
            <!-- Settings -->
            <label class="flex items-center gap-3 cursor-pointer p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-5 h-5 text-green-500 rounded border-gray-300 focus:ring-green-500">
                <div>
                    <p class="text-sm font-medium text-gray-800">Status Aktif</p>
                    <p class="text-xs text-gray-500">Tampilkan produk ini</p>
                </div>
            </label>

            <label class="flex items-center gap-3 cursor-pointer p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $product->is_popular) ? 'checked' : '' }} class="w-5 h-5 text-amber-500 rounded border-gray-300 focus:ring-amber-500">
                <div>
                    <p class="text-sm font-medium text-gray-800">Tandai Populer</p>
                    <p class="text-xs text-gray-500">Beri label khusus</p>
                </div>
            </label>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}"
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Update Produk
            </button>
            <a href="{{ route('admin.topup-products.index', ['game_id' => $product->game_id]) }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
