@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Selamat Datang, {{ Auth::guard('admin')->user()->name }}! 👋</h1>
    <p class="text-gray-500">Ringkasan performa penjualan dan operasional hari ini.</p>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Top Up Stats -->
    <div class="bg-white rounded-2xl p-6 border border-blue-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Transaksi Top Up</p>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900">{{ number_format($topupToday) }}</h3>
            <p class="text-sm text-gray-500 mt-1">Total Hari Ini</p>
        </div>
    </div>

    <!-- Joki Stats -->
    <div class="bg-white rounded-2xl p-6 border border-amber-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full opacity-50"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Transaksi Joki</p>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900">{{ number_format($jokiToday) }}</h3>
            <p class="text-sm text-gray-500 mt-1">Total Hari Ini</p>
        </div>
    </div>

    <!-- Revenue Top Up -->
    <div class="bg-white rounded-2xl p-6 border border-green-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full opacity-50"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase">Revenue Top Up</p>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900">Rp {{ number_format($revenueTopupMonth, 0, ',', '.') }}</h3>
            <p class="text-sm text-gray-500 mt-1">Bulan Ini</p>
        </div>
    </div>

    <!-- Revenue Joki -->
    <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-50 rounded-full opacity-50"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase">Revenue Joki</p>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900">Rp {{ number_format($revenueJokiMonth, 0, ',', '.') }}</h3>
            <p class="text-sm text-gray-500 mt-1">Bulan Ini</p>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chart Area -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-gray-900">Tren Transaksi (30 Hari)</h3>
            <select id="chartFilter" class="text-sm border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 py-1.5 px-3 bg-gray-50 text-gray-700">
                <option value="all">Semua (Top Up + Joki)</option>
                <option value="topup">Top Up Saja</option>
                <option value="joki">Joki Saja</option>
            </select>
        </div>
        <div class="h-72 w-full">
            <canvas id="transactionChart"></canvas>
        </div>
    </div>

    <!-- Need Attention -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-bell text-amber-500"></i> Perlu Tindakan Segera
        </h3>
        
        <div class="space-y-4">
            @if($slaJoki > 0)
            <!-- Alert Item -->
            <div class="flex gap-4 p-4 rounded-xl bg-red-50 border border-red-100 relative overflow-hidden">
                <div class="w-1 bg-red-500 absolute left-0 top-0 bottom-0"></div>
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-red-500 shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $slaJoki }} Order Joki Lewat SLA</p>
                    <p class="text-xs text-gray-600 mt-1">Lebih dari 10 menit belum direspon. Segera proses!</p>
                    <a href="{{ route('admin.orders-joki.index', ['status' => 'waiting_process']) }}" class="inline-block mt-2 text-xs font-bold text-red-600 hover:text-red-700">Lihat Order &rarr;</a>
                </div>
            </div>
            @endif

            @if($unreadChats > 0)
            <!-- Alert Item -->
            <div class="flex gap-4 p-4 rounded-xl bg-primary-50 border border-primary-100 relative overflow-hidden">
                <div class="w-1 bg-primary-500 absolute left-0 top-0 bottom-0"></div>
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-primary-500 shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-messages"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $unreadChats }} Chat Belum Dibalas</p>
                    <p class="text-xs text-gray-600 mt-1">Pelanggan sedang menunggu balasan Anda.</p>
                    <a href="{{ route('admin.chat.index', ['status' => 'open']) }}" class="inline-block mt-2 text-xs font-bold text-primary-600 hover:text-primary-700">Buka Chat &rarr;</a>
                </div>
            </div>
            @endif
            
            @if($slaJoki == 0 && $unreadChats == 0)
            <div class="text-center py-6 text-gray-400">
                <i class="fa-solid fa-check-circle text-4xl mb-3 text-green-400"></i>
                <p class="text-sm">Semua aman terkendali.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('transactionChart').getContext('2d');
    const rawData = @json($chartData);
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: rawData.labels,
            datasets: [
                {
                    label: 'Top Up',
                    data: rawData.topupData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Joki',
                    data: rawData.jokiData,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    document.getElementById('chartFilter').addEventListener('change', function(e) {
        const val = e.target.value;
        if (val === 'all') {
            chart.data.datasets[0].hidden = false;
            chart.data.datasets[1].hidden = false;
        } else if (val === 'topup') {
            chart.data.datasets[0].hidden = false;
            chart.data.datasets[1].hidden = true;
        } else if (val === 'joki') {
            chart.data.datasets[0].hidden = true;
            chart.data.datasets[1].hidden = false;
        }
        chart.update();
    });
});
</script>
@endpush
