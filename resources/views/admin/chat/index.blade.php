@extends('layouts.admin')

@section('title', 'Live Chat')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Live Chat Pelanggan</h1>
        <p class="text-sm text-gray-500 mt-1">Balas pesan dan keluhan pelanggan dari website.</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cari Tiket / Email / Nama</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div class="w-40">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
            <select name="status" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="open" {{ request('status', 'open') == 'open' ? 'selected' : '' }}>Aktif (Open)</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Selesai (Closed)</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-5 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4">Tiket ID</th>
                    <th class="p-4">Pelanggan</th>
                    <th class="p-4">Pesan Terakhir</th>
                    <th class="p-4 text-center">Waktu</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($conversations as $conv)
                <tr class="hover:bg-gray-50/50 transition-colors {{ $conv->unread_count > 0 ? 'bg-blue-50/30' : '' }}">
                    <td class="p-4 font-mono text-xs font-bold text-gray-900">
                        {{ $conv->ticket_id }}
                    </td>
                    <td class="p-4">
                        <p class="font-bold text-gray-900">{{ $conv->customer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $conv->customer_email }}</p>
                    </td>
                    <td class="p-4">
                        <p class="text-gray-800 text-sm truncate max-w-xs {{ $conv->unread_count > 0 ? 'font-bold' : '' }}">
                            {{ $conv->lastMessage->message ?? 'Belum ada pesan' }}
                        </p>
                    </td>
                    <td class="p-4 text-center text-xs text-gray-500">
                        {{ $conv->updated_at->diffForHumans() }}
                    </td>
                    <td class="p-4 text-center">
                        @if($conv->status === 'open')
                            <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">Open</span>
                        @else
                            <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">Closed</span>
                        @endif
                        
                        @if($conv->unread_count > 0)
                            <span class="ml-1 inline-flex py-1 px-2 rounded-md text-[10px] font-bold bg-red-500 text-white">{{ $conv->unread_count }} Baru</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.chat.show', $conv) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 hover:bg-primary-100 text-primary-700 font-medium rounded-lg text-xs transition-colors">
                            Buka Chat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-gray-500">
                        <i class="fa-solid fa-comments text-3xl text-gray-200 mb-3 block"></i>
                        Belum ada percakapan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($conversations->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $conversations->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
