@extends('layouts.admin')

@section('title', 'Tambah Customer')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-900">Tambah Customer</h1>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <input type="password" name="password" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        
        <div class="pt-4 border-t border-gray-50 flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm">
                Simpan Customer
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
