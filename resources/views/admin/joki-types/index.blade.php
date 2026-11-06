@extends('layouts.admin')

@section('title', 'Tipe Joki')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Tipe Joki</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola jenis layanan joki (Rank, Winrate, Classic) untuk setiap game.</p>
    </div>
    <div class="flex items-center gap-3 w-full sm:w-auto">
        <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <select name="game_id" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="this.form.submit()">
                <option value="">Semua Game</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.joki-types.create', ['game_id' => request('game_id')]) }}" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-xl transition-colors shadow-sm inline-flex items-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Tambah
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4">Tipe Joki</th>
                    <th class="p-4">Game</th>
                    <th class="p-4 text-center">Unit</th>
                    <th class="p-4 text-center">Harga Flat</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($jokiTypes as $type)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $type->name }}</p>
                                <p class="text-xs text-gray-500">Min Order: {{ $type->min_order }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-gray-600 font-medium">
                        {{ $type->game->name }}
                    </td>
                    <td class="p-4 text-center text-gray-600 font-medium">
                        {{ $type->unit_name }}
                    </td>
                    <td class="p-4 text-center">
                        @if($type->is_flat_price)
                            <i class="fa-solid fa-check text-green-500"></i>
                        @else
                            <i class="fa-solid fa-minus text-gray-300"></i>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($type->is_active)
                            <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">Aktif</span>
                        @else
                            <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">Inaktif</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.joki-tiers.index', ['joki_type_id' => $type->id]) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Kelola Paket/Tier">
                                <i class="fa-solid fa-layer-group"></i>
                            </a>
                            <a href="{{ route('admin.joki-types.edit', $type) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.joki-types.destroy', $type) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tipe joki ini? Semua paket di dalamnya juga akan terhapus.');">
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
                    <td colspan="6" class="p-8 text-center text-gray-500">
                        Belum ada tipe joki.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
