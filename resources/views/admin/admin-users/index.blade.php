@extends('layouts.admin')

@section('title', 'Admin Users')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Users</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola akun administrator website.</p>
    </div>
    <a href="{{ route('admin.admin-users.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Admin
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4">Nama</th>
                    <th class="p-4">Email</th>
                    <th class="p-4 text-center">Role</th>
                    <th class="p-4 text-center">Dibuat Pada</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($admins as $admin)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 font-bold text-gray-900">
                        {{ $admin->name }}
                        @if(auth()->guard('admin')->id() === $admin->id)
                            <span class="ml-2 text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full uppercase">Anda</span>
                        @endif
                    </td>
                    <td class="p-4 text-gray-600">{{ $admin->email }}</td>
                    <td class="p-4 text-center">
                        <span class="text-[10px] bg-gray-100 text-gray-700 px-2 py-1 rounded-md uppercase font-bold border border-gray-200">
                            {{ $admin->role }}
                        </span>
                    </td>
                    <td class="p-4 text-center text-gray-500">
                        {{ $admin->created_at->format('d M Y') }}
                    </td>
                    <td class="p-4 text-right flex justify-end gap-2">
                        <a href="{{ route('admin.admin-users.edit', $admin) }}" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        @if(auth()->guard('admin')->id() !== $admin->id && \App\Models\Admin::count() > 1)
                        <form action="{{ route('admin.admin-users.destroy', $admin) }}" method="POST" onsubmit="return confirm('Hapus admin ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center text-gray-500">Belum ada admin lain.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($admins->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $admins->links() }}
    </div>
    @endif
</div>
@endsection
