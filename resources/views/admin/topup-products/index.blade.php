@extends('layouts.admin')

@section('title', 'Produk Top Up')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Produk Top Up</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola harga dan produk top up untuk setiap game.</p>
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
        <a href="{{ route('admin.topup-products.create', ['game_id' => request('game_id')]) }}" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-xl transition-colors shadow-sm inline-flex items-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Tambah
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4">Produk</th>
                    <th class="p-4">Game</th>
                    <th class="p-4 text-right">Harga Jual</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center text-primary-600 flex-shrink-0">
                                @if(in_array($product->type, ['membership', 'pass']))
                                    <i class="fa-solid fa-crown"></i>
                                @else
                                    <i class="fa-solid fa-gem"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $product->name }}</p>
                                <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">{{ $product->type }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-gray-600 font-medium">
                        {{ $product->game->name }}
                    </td>
                    <td class="p-4 text-right">
                        <p class="font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @if($product->modal_price)
                        <p class="text-xs text-gray-400">Modal: Rp {{ number_format($product->modal_price, 0, ',', '.') }}</p>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex flex-col items-center gap-1">
                            @if($product->is_active)
                                <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                    Inaktif
                                </span>
                            @endif
                            
                            @if($product->is_popular)
                                <span class="inline-flex py-1 px-2 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                    <i class="fa-solid fa-fire mr-1"></i> Popular
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.topup-products.edit', $product) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.topup-products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?');">
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
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        Belum ada produk top up.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $products->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
