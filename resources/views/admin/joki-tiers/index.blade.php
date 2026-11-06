@extends('layouts.admin')

@section('title', 'Paket / Tier Joki')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.joki-types.index', ['game_id' => $jokiType->game_id]) }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 mb-2 inline-block">&larr; Kembali ke Tipe Joki</a>
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mt-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tier: {{ $jokiType->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Game: {{ $jokiType->game->name }} | Satuan: {{ $jokiType->unit_name }} | Harga: {{ $jokiType->is_flat_price ? 'Flat' : 'Bervariasi' }}</p>
        </div>
        <a href="{{ route('admin.joki-tiers.create', ['joki_type_id' => $jokiType->id]) }}" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-xl transition-colors shadow-sm inline-flex items-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Tambah Tier
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4 w-16 text-center">Urutan</th>
                    <th class="p-4">Nama Tier</th>
                    <th class="p-4 text-right">Harga per {{ $jokiType->unit_name }}</th>
                    <th class="p-4 text-center">Min. Order</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($tiers as $tier)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 text-center font-bold text-gray-400">
                        {{ $tier->tier_order }}
                    </td>
                    <td class="p-4">
                        <p class="font-bold text-gray-900">{{ $tier->tier_name }}</p>
                        @if($tier->description)
                            <p class="text-xs text-gray-500 mt-1 truncate max-w-xs">{{ $tier->description }}</p>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <p class="font-bold text-gray-900">Rp {{ number_format($tier->price_per_unit, 0, ',', '.') }}</p>
                    </td>
                    <td class="p-4 text-center text-gray-600 font-medium">
                        {{ $tier->min_order }}
                    </td>
                    <td class="p-4 text-center">
                        @if($tier->is_active)
                            <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">Aktif</span>
                        @else
                            <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">Inaktif</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.joki-tiers.edit', $tier) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.joki-tiers.destroy', $tier) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tier ini?');">
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
                        Belum ada tier untuk tipe joki ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
