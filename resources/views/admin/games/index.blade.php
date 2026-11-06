@extends('layouts.admin')

@section('title', 'Manajemen Game')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Game</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar game untuk layanan Top Up & Joki.</p>
    </div>
    <a href="{{ route('admin.games.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-xl transition-colors shadow-sm inline-flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Game
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4">Game</th>
                    <th class="p-4 text-center">Fitur</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($games as $game)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                @if($game->banner)
                                    <img src="{{ asset('storage/' . $game->banner) }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image text-xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $game->name }}</p>
                                <p class="text-xs text-gray-500">Slug: {{ $game->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex flex-wrap justify-center gap-2">
                            @if($game->available_topup)
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-lg border border-blue-100">Top Up</span>
                            @endif
                            @if($game->available_joki)
                                <span class="px-2 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-lg border border-amber-100">Joki</span>
                            @endif
                            @if($game->has_server_id)
                                <span class="px-2 py-1 bg-purple-50 text-purple-600 text-[10px] font-bold rounded-lg border border-purple-100">Server ID</span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        @if($game->is_active)
                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span> Inaktif
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.games.edit', $game) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.games.destroy', $game) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus game ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-500">
                        Belum ada data game. <a href="{{ route('admin.games.create') }}" class="text-primary-600 font-medium">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
