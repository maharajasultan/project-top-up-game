@extends('layouts.admin')

@section('title', 'Edit Metode Login')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.joki-login-methods.index', ['game_id' => $method->game_id]) }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Daftar Metode</a>
    <h1 class="text-2xl font-bold text-gray-900">Edit Metode Login: {{ $method->method_name }}</h1>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden max-w-3xl">
    <form action="{{ route('admin.joki-login-methods.update', $method) }}" method="POST" class="p-6 sm:p-8">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Game -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Game <span class="text-red-500">*</span></label>
                <select name="game_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ old('game_id', $method->game_id) == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
                @error('game_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Metode -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Metode Login <span class="text-red-500">*</span></label>
                <input type="text" name="method_name" value="{{ old('method_name', $method->method_name) }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('method_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                <!-- Settings -->
                <label class="flex items-center gap-3 cursor-pointer p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors h-full">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $method->is_active) ? 'checked' : '' }} class="w-5 h-5 text-green-500 rounded border-gray-300 focus:ring-green-500">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Status Aktif</p>
                        <p class="text-xs text-gray-500">Tampilkan ke form order</p>
                    </div>
                </label>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan Tampil <span class="text-red-500">*</span></label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $method->sort_order) }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Update Metode
            </button>
            <a href="{{ route('admin.joki-login-methods.index', ['game_id' => $method->game_id]) }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
