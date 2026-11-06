@extends('layouts.admin')

@section('title', 'Log Aktivitas (Audit Trail)')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Log Aktivitas Admin</h1>
        <p class="text-sm text-gray-500 mt-1">Audit trail seluruh aktivitas perubahan data oleh admin.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cari Aktivitas</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi..."
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div class="w-48">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Admin</label>
            <select name="admin_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Admin</option>
                @foreach($admins as $adm)
                    <option value="{{ $adm->id }}" {{ request('admin_id') == $adm->id ? 'selected' : '' }}>{{ $adm->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-40">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Modul</label>
            <select name="module" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Modul</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'admin_id', 'module']))
            <a href="{{ route('admin.activity-logs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                    <th class="p-4 w-48">Waktu</th>
                    <th class="p-4 w-48">Admin</th>
                    <th class="p-4 w-40">Modul</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4 w-32">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 text-gray-500 text-xs">
                        {{ $log->created_at->format('d M Y') }}<br>
                        <span class="font-bold text-gray-700">{{ $log->created_at->format('H:i:s') }}</span>
                    </td>
                    <td class="p-4">
                        <span class="font-bold text-gray-900">{{ $log->admin->name ?? 'System' }}</span><br>
                        <span class="text-xs text-gray-500">{{ $log->admin->email ?? '-' }}</span>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex py-1 px-2.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase">
                            {{ $log->module ?? 'UMUM' }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-800">
                        {{ $log->description }}
                        <div class="text-[10px] text-gray-400 mt-1 font-mono uppercase">{{ $log->action }}</div>
                    </td>
                    <td class="p-4 text-xs font-mono text-gray-500">
                        {{ $log->ip_address ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center text-gray-500">
                        <i class="fa-solid fa-list-check text-3xl text-gray-200 mb-3 block"></i>
                        Belum ada aktivitas yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
