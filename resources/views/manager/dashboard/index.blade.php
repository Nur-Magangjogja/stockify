@extends('layouts.app')
@section('title', 'Dashboard Manajer')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <x-stat-card color="green" label="Masuk Hari Ini"
        :value="$stats['today_in']"
        sub="transaksi dikonfirmasi">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card color="red" label="Keluar Hari Ini"
        :value="$stats['today_out']"
        sub="transaksi dikonfirmasi">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8V20m0 0l4-4m-4 4l-4-4"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card color="blue" label="Stok Menipis"
        :value="$summary['low_stock_count']"
        sub="produk butuh restock">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card color="amber" label="Total Produk"
        :value="$summary['total_products']"
        sub="produk aktif">
        <x-slot:icon>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </x-slot:icon>
    </x-stat-card>

</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
    {{-- Aksi Cepat --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
        <h3 class="font-semibold text-white mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('manager.stocks.in.create') }}"
               class="flex items-center gap-3 p-3 bg-green-900/20 border border-green-700/40 rounded-xl hover:bg-green-900/30 transition-all">
                <div class="w-8 h-8 bg-green-600/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-green-300">Barang Masuk</span>
            </a>
            <a href="{{ route('manager.stocks.out.create') }}"
               class="flex items-center gap-3 p-3 bg-red-900/20 border border-red-700/40 rounded-xl hover:bg-red-900/30 transition-all">
                <div class="w-8 h-8 bg-red-600/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8V20m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-red-300">Barang Keluar</span>
            </a>
            <a href="{{ route('manager.stocks.opname.index') }}"
               class="flex items-center gap-3 p-3 bg-blue-900/20 border border-blue-700/40 rounded-xl hover:bg-blue-900/30 transition-all">
                <div class="w-8 h-8 bg-blue-600/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-blue-300">Stock Opname</span>
            </a>
            <a href="{{ route('manager.reports.stock') }}"
               class="flex items-center gap-3 p-3 bg-purple-900/20 border border-purple-700/40 rounded-xl hover:bg-purple-900/30 transition-all">
                <div class="w-8 h-8 bg-purple-600/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-purple-300">Laporan Stok</span>
            </a>
        </div>
    </div>

    {{-- Stok Menipis --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
            <h3 class="font-semibold text-white">⚠️ Stok Menipis</h3>
            <a href="{{ route('manager.products.index', ['stock_status' => 'low']) }}"
               class="text-xs text-blue-400 hover:text-blue-300">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-700">
            @forelse($lowStockItems as $product)
            <div class="flex items-center gap-3 px-5 py-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->code }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-sm font-bold {{ $product->stock <= 0 ? 'text-red-400' : 'text-amber-400' }}">
                        {{ $product->stock }} {{ $product->unit }}
                    </span>
                </div>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-gray-500 text-sm">Semua stok dalam kondisi baik 👍</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
