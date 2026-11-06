@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.admin-users.index') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-900">Edit Admin</h1>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.admin-users.update', $adminUser) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $adminUser->name) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $adminUser->email) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        
        <div class="pt-4 mt-4 border-t border-gray-100">
            <p class="text-sm font-bold text-gray-700 mb-3">Ubah Password (Opsional)</p>
            <p class="text-xs text-gray-500 mb-4">Biarkan kosong jika tidak ingin mengubah password.</p>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>
        </div>
        
        <div class="pt-4 border-t border-gray-50 flex gap-3 mt-6">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm">
                Update Admin
            </button>
            <a href="{{ route('admin.admin-users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
