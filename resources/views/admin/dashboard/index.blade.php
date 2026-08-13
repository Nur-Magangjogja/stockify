@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', '')

@section('content')
<x-welcome-banner />

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">

    <x-stat-card color="blue" label="Total Produk"
        :value="number_format($stats['total_products'])"
        :sub="$stats['low_stock_count'] . ' produk yang ada saat ini'">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card color="purple" label="Total Stok"
        :value="number_format($stats['total_stock_qty'])"
        sub="Jumlah item fisik secara keseluruhan">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    @php
        $val = $stats['total_stock_value'];
        if ($val >= 1000000000000) {
            $fmt = number_format($val / 1000000000000, 1, ',', '') . ' T';
        } elseif ($val >= 1000000000) {
            $fmt = number_format($val / 1000000000, 1, ',', '') . ' M';
        } elseif ($val >= 1000000) {
            $fmt = number_format($val / 1000000, 1, ',', '') . ' Jt';
        } elseif ($val >= 1000) {
            $fmt = number_format($val / 1000, 1, ',', '') . ' Rb';
        } else {
            $fmt = number_format($val, 0, ',', '.');
        }
        $fmt = str_replace(',0 ', ' ', $fmt); // Hapus angka desimal jika bulat (misal 1,0 Jt jadi 1 Jt)
    @endphp
    <x-stat-card color="green" label="Nilai Stok"
        :value="'Rp ' . $fmt"
        sub="Total nilai inventaris">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card color="indigo" label="Total Transaksi"
        :value="number_format($stats['total_transactions'])"
        sub="Total transaksi tercatat dalam sistem">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card color="amber" label="Masuk Hari Ini"
        :value="$stats['today_in']"
        :sub="$stats['month_in_qty'] . ' unit bulan ini'">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card color="red" label="Keluar Hari Ini"
        :value="$stats['today_out']"
        :sub="$stats['month_out_qty'] . ' unit bulan ini'">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8V20M17 20l4-4m-4 4l-4-4M7 4L3 8m4-4l4 4M7 4v12"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    {{-- Chart --}}
    <div class="xl:col-span-2 bg-gray-800/60 backdrop-blur-xl rounded-2xl border border-white/10 p-6 shadow-2xl flex flex-col">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-6 gap-4 w-full">
            <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-4 w-full lg:w-auto">
                <div>
                    <h3 class="text-base font-semibold text-white" id="mainChartTitle">Grafik Transaksi</h3>
                    <p class="text-xs text-gray-400" id="mainChartSubtitle">{{ $stats['transaction_chart']['period_formatted'] }}</p>
                </div>
                
                {{-- Month Filter --}}
                <form method="GET" action="{{ route('admin.dashboard') }}" class="sm:ml-4 w-full sm:w-auto" id="transactionChartFilter">
                    <input type="month" name="period" value="{{ $stats['transaction_chart']['period'] }}" 
                           onchange="this.form.submit()" 
                           class="w-full sm:w-auto bg-gray-900 border border-gray-700 text-gray-300 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1.5 cursor-pointer">
                </form>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex bg-gray-900/50 p-1 rounded-lg border border-gray-700/50">
                    <button type="button" id="btnShowTransaction" class="px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-blue-500/20 text-blue-400 whitespace-nowrap">
                        Transaksi
                    </button>
                    <button type="button" id="btnShowCategory" class="px-3 py-1.5 text-xs font-medium rounded-md transition-all text-gray-400 hover:text-gray-300 whitespace-nowrap">
                        Stok Kategori
                    </button>
                </div>

                {{-- Legend for Transaction Chart --}}
                <div id="transactionLegend" class="flex flex-wrap items-center gap-4 text-xs ml-0 sm:ml-2">
                    <span class="flex items-center gap-1.5 text-gray-400">
                        <span class="w-3 h-3 bg-blue-500 rounded-full inline-block shrink-0"></span> Masuk
                    </span>
                    <span class="flex items-center gap-1.5 text-gray-400">
                        <span class="w-3 h-3 bg-red-500 rounded-full inline-block shrink-0"></span> Keluar
                    </span>
                </div>
            </div>
        </div>

        {{-- Chart Containers --}}
        <div class="relative w-full flex-1 min-h-[400px] overflow-hidden flex flex-col">
            {{-- Transaction Chart Container --}}
            <div id="transactionChartContainer" class="w-full h-full flex-1" data-subtitle="{{ $stats['transaction_chart']['period_formatted'] }}">
                <canvas id="transactionChart" 
                        data-labels="{{ json_encode($stats['transaction_chart']['labels']) }}"
                        data-masuk="{{ json_encode($stats['transaction_chart']['masuk']) }}"
                        data-keluar="{{ json_encode($stats['transaction_chart']['keluar']) }}">
                </canvas>
            </div>
            
            {{-- Category Chart Container --}}
            @php
                $catLabels = [];
                $catData = [];
                $catNames = [];
                
                // Batasi data agar tidak merender ribuan bar yang menyebabkan canvas crash (maksimal 300 produk)
                $safeChartData = !empty($stats['product_stock_chart']) ? array_slice($stats['product_stock_chart'], 0, 300) : [];
                
                foreach($safeChartData as $prod) {
                    // Limit label length if too long
                    $label = strlen($prod->product_name) > 20 ? substr($prod->product_name, 0, 20).'...' : $prod->product_name;
                    $catLabels[] = $label;
                    $catData[] = $prod->current_stock;
                    $catNames[] = $prod->category_name;
                }
                
                // Hitung lebar canvas, max 15000px agar tidak crash di browser
                $canvasWidth = min(max(count($catLabels) * 40, 800), 15000); 
            @endphp
            <div id="categoryChartContainer" class="w-full h-full flex-1 overflow-x-auto hidden overflow-y-hidden pb-4 select-none scroll-smooth">
                <div style="width: {{ $canvasWidth }}px; height: 400px;">
                    <canvas id="categoryChart" 
                            data-labels="{{ json_encode($catLabels) }}"
                            data-stock="{{ json_encode($catData) }}"
                            data-categories="{{ json_encode($catNames) }}">
                    </canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Stok per Kategori --}}
    <div class="bg-gray-800/60 backdrop-blur-xl rounded-2xl border border-white/10 p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-white">Stok per Kategori</h3>
            <a href="{{ route('admin.categories.index') }}" class="text-xs text-blue-400 hover:text-blue-300">Lihat semua</a>
        </div>
        @if(!empty($stats['stock_by_category']))
        <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
            @php $maxStock = max(array_column($stats['stock_by_category'], 'total_stock')); @endphp
            @foreach($stats['stock_by_category'] as $cat)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-300 truncate">{{ $cat->name }}</span>
                    <span class="text-gray-400 ml-2 flex-shrink-0">{{ number_format($cat->total_stock) }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-1.5">
                    <div class="bg-blue-500 h-1.5 rounded-full"
                         style="width: {{ $maxStock > 0 ? round(($cat->total_stock / $maxStock) * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-sm text-center py-8">Belum ada data</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
    {{-- Low Stock Alert --}}
    <div class="bg-gray-800/60 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-white/5 bg-gray-900/20">
            <h3 class="font-semibold text-white">⚠️ Stok Menipis</h3>
            <a href="{{ route('admin.products.index', ['stock_status' => 'low']) }}"
               class="text-xs text-blue-400 hover:text-blue-300">Lihat semua</a>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($lowStockItems as $product)
            <div class="flex items-center gap-4 px-6 py-4 hover:bg-white/[0.02] transition-colors group">
                <div class="w-9 h-9 rounded-lg bg-gray-700 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    <img src="{{ $product->image ? $product->image_url : 'https://placehold.co/600x400/374151/9ca3af?text=No+Image' }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->code }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-sm font-bold {{ $product->stock <= 0 ? 'text-red-400' : 'text-amber-400' }}">
                        {{ $product->stock }} {{ $product->unit }}
                    </span>
                    <p class="text-xs text-gray-500">min: {{ $product->min_stock }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 text-sm py-8">Semua stok dalam kondisi baik 👍</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-gray-800/60 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-white/5 bg-gray-900/20">
            <h3 class="font-semibold text-white">Aktivitas Terbaru</h3>
            <a href="{{ route('admin.reports.activities') }}"
               class="text-xs text-blue-400 hover:text-blue-300">Lihat semua</a>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($recentActivities as $activity)
            <div class="flex items-start gap-4 px-6 py-4 hover:bg-white/[0.02] transition-colors group">
                <img src="{{ $activity->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=System&background=374151&color=9ca3af' }}"
                     class="w-7 h-7 rounded-full flex-shrink-0 mt-0.5" alt="">
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-300">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 text-sm py-8">Belum ada aktivitas</p>
            @endforelse
        </div>
    </div>
</div>

{{-- User Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    @foreach([
        ['label' => 'Total Pengguna', 'value' => $userStats['total_users'],    'bg' => 'bg-blue-500/10', 'text' => 'text-blue-400', 'border' => 'border-blue-500/20'],
        ['label' => 'Admin',          'value' => $userStats['total_admin'],     'bg' => 'bg-purple-500/10', 'text' => 'text-purple-400', 'border' => 'border-purple-500/20'],
        ['label' => 'Manajer',        'value' => $userStats['total_managers'],  'bg' => 'bg-green-500/10', 'text' => 'text-green-400', 'border' => 'border-green-500/20'],
        ['label' => 'Staff',          'value' => $userStats['total_staff'],     'bg' => 'bg-amber-500/10', 'text' => 'text-amber-400', 'border' => 'border-amber-500/20'],
    ] as $stat)
    <div class="bg-gray-800/40 backdrop-blur-md rounded-2xl border {{ $stat['border'] }} p-5 text-center transition-transform hover:-translate-y-1">
        <div class="w-10 h-10 {{ $stat['bg'] }} {{ $stat['text'] }} rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <p class="text-2xl font-bold text-white mb-1">{{ $stat['value'] }}</p>
        <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>
@endsection
