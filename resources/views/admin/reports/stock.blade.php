@extends('layouts.app')
@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok Barang')

@section('content')
@php $prefix = auth()->user()->isAdmin() ? 'admin' : 'manager'; @endphp
{{-- Summary Cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
    @foreach([
        ['label' => 'Total Produk', 'value' => $report['total_products'], 'color' => 'text-blue-400'],
        ['label' => 'Nilai Stok', 'value' => 'Rp ' . number_format($report['total_stock_value'], 0, ',', '.'), 'color' => 'text-green-400'],
        ['label' => 'Stok Menipis', 'value' => $report['low_stock_count'], 'color' => 'text-amber-400'],
        ['label' => 'Stok Habis', 'value' => $report['out_of_stock'], 'color' => 'text-red-400'],
    ] as $stat)
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
        <p class="text-xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-400 mb-1">Kategori</label>
            <select name="category_id" class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Status Stok</label>
            <select name="stock_status" class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2">
                <option value="">Semua</option>
                <option value="low" {{ ($filters['stock_status'] ?? '') === 'low' ? 'selected' : '' }}>Menipis</option>
                <option value="out" {{ ($filters['stock_status'] ?? '') === 'out' ? 'selected' : '' }}>Habis</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-all">Tampilkan</button>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" target="_blank"
           class="flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Excel
        </a>
    </form>
</div>

{{-- Table --}}
<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="border-b border-gray-700">
                <tr>
                    <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase">Produk</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase">Kategori</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase text-center">Stok</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase text-center">Min Stok</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase text-right">Nilai Stok</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($report['products'] as $product)
                <tr class="hover:bg-gray-750 transition-colors">
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-white">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $product->code }}</p>
                    </td>
                    <td class="px-4 py-3.5 text-gray-300">{{ $product->category_name ?? '-' }}</td>
                    <td class="px-4 py-3.5 text-center font-semibold {{ $product->stock <= 0 ? 'text-red-400' : ($product->is_low_stock ? 'text-amber-400' : 'text-green-400') }}">
                        {{ number_format($product->stock) }} {{ $product->unit }}
                    </td>
                    <td class="px-4 py-3.5 text-center text-gray-400">{{ $product->min_stock }}</td>
                    <td class="px-4 py-3.5 text-right text-gray-300">Rp {{ number_format($product->stock * $product->buy_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3.5">
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $product->stock <= 0 ? 'bg-red-900/40 text-red-400' : ($product->is_low_stock ? 'bg-amber-900/40 text-amber-400' : 'bg-green-900/40 text-green-400') }}">
                            {{ $product->stock_status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <x-table-empty :colspan="6" message="Tidak ada data" />
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
