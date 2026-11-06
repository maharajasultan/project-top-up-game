@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Laporan Transaksi</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan transaksi top up dan joki berdasarkan rentang tanggal.</p>
    </div>
</div>

<!-- Date Filter -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Mulai Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-48 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-48 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm">
                Tampilkan
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Top Up Stats -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-6 opacity-5">
            <i class="fa-solid fa-bolt text-8xl"></i>
        </div>
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 relative z-10">
            <i class="fa-solid fa-bolt text-blue-500"></i> Ringkasan Top Up
        </h2>
        
        <div class="space-y-4 relative z-10">
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
                <span class="text-gray-500 font-medium">Total Transaksi</span>
                <span class="text-xl font-bold text-gray-900">{{ number_format($topupStats['total_orders']) }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
                <span class="text-gray-500 font-medium">Transaksi Sukses</span>
                <span class="text-xl font-bold text-green-600">{{ number_format($topupStats['success_orders']) }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-500 font-medium">Total Pendapatan (Sukses)</span>
                <span class="text-2xl font-black text-primary-600">Rp {{ number_format($topupStats['total_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100 relative z-10">
            <a href="{{ route('admin.reports.export', ['type' => 'topup', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="w-full flex items-center justify-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold py-3 rounded-xl transition-colors border border-blue-100">
                <i class="fa-solid fa-file-csv"></i> Export CSV Top Up
            </a>
        </div>
    </div>

    <!-- Joki Stats -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-6 opacity-5">
            <i class="fa-solid fa-trophy text-8xl"></i>
        </div>
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 relative z-10">
            <i class="fa-solid fa-trophy text-amber-500"></i> Ringkasan Jasa Joki
        </h2>
        
        <div class="space-y-4 relative z-10">
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
                <span class="text-gray-500 font-medium">Total Transaksi</span>
                <span class="text-xl font-bold text-gray-900">{{ number_format($jokiStats['total_orders']) }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
                <span class="text-gray-500 font-medium">Joki Selesai</span>
                <span class="text-xl font-bold text-green-600">{{ number_format($jokiStats['completed_orders']) }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-500 font-medium">Total Pendapatan (Selesai)</span>
                <span class="text-2xl font-black text-primary-600">Rp {{ number_format($jokiStats['total_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100 relative z-10">
            <a href="{{ route('admin.reports.export', ['type' => 'joki', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="w-full flex items-center justify-center gap-2 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold py-3 rounded-xl transition-colors border border-amber-100">
                <i class="fa-solid fa-file-csv"></i> Export CSV Joki
            </a>
        </div>
    </div>
</div>
@endsection
