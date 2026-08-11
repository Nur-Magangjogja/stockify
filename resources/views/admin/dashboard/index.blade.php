@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan sistem Stockify')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <x-stat-card color="blue" label="Total Produk"
        :value="number_format($stats['total_products'])"
        :sub="$stats['low_stock_count'] . ' stok menipis'">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card color="green" label="Nilai Stok"
        :value="'Rp ' . number_format($stats['total_stock_value'], 0, ',', '.')"
        sub="Total nilai inventaris">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
    {{-- Chart --}}
    <div class="xl:col-span-2 bg-gray-800 rounded-xl border border-gray-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-semibold text-white">Grafik Transaksi</h3>
                <p class="text-xs text-gray-400">{{ now()->translatedFormat('F Y') }}</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5 text-gray-400">
                    <span class="w-3 h-3 bg-blue-500 rounded-full inline-block"></span> Masuk
                </span>
                <span class="flex items-center gap-1.5 text-gray-400">
                    <span class="w-3 h-3 bg-red-500 rounded-full inline-block"></span> Keluar
                </span>
            </div>
        </div>
        <canvas id="transactionChart" height="100"></canvas>
    </div>

    {{-- Stok per Kategori --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
        <h3 class="text-base font-semibold text-white mb-4">Stok per Kategori</h3>
        @if(!empty($stats['stock_by_category']))
        <div class="space-y-3">
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

<div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
    {{-- Low Stock Alert --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
            <h3 class="font-semibold text-white">⚠️ Stok Menipis</h3>
            <a href="{{ route('admin.products.index', ['stock_status' => 'low']) }}"
               class="text-xs text-blue-400 hover:text-blue-300">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-700">
            @forelse($lowStockItems as $product)
            <div class="flex items-center gap-3 px-5 py-3">
                <div class="w-9 h-9 rounded-lg bg-gray-700 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($product->image)
                        <img src="{{ $product->image_url }}" class="w-full h-full object-cover" alt="">
                    @else
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    @endif
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
    <div class="bg-gray-800 rounded-xl border border-gray-700">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
            <h3 class="font-semibold text-white">Aktivitas Terbaru</h3>
            <a href="{{ route('admin.reports.activities') }}"
               class="text-xs text-blue-400 hover:text-blue-300">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-700">
            @forelse($recentActivities as $activity)
            <div class="flex items-start gap-3 px-5 py-3">
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
<div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
    @foreach([
        ['label' => 'Total Pengguna', 'value' => $userStats['total_users'],    'color' => 'text-blue-400'],
        ['label' => 'Admin',          'value' => $userStats['total_admin'],     'color' => 'text-purple-400'],
        ['label' => 'Manajer',        'value' => $userStats['total_managers'],  'color' => 'text-green-400'],
        ['label' => 'Staff',          'value' => $userStats['total_staff'],     'color' => 'text-amber-400'],
    ] as $stat)
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>
@endsection
