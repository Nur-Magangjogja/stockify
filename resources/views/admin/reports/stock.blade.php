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
    <x-search-filter :action="request()->url()" :hideDefault="true">
        <div>
            <label class="block text-xs text-gray-400 mb-1">Kategori</label>
            <select name="category_id" class="input-field text-sm rounded-lg px-3 py-2">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Status Stok</label>
            <select name="stock_status" class="input-field text-sm rounded-lg px-3 py-2">
                <option value="">Semua</option>
                <option value="low" {{ ($filters['stock_status'] ?? '') === 'low' ? 'selected' : '' }}>Menipis</option>
                <option value="out" {{ ($filters['stock_status'] ?? '') === 'out' ? 'selected' : '' }}>Habis</option>
            </select>
        </div>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" target="_blank"
           class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-all w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Excel
        </a>
    </x-search-filter>
</div>

{{-- Table --}}
<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-standard">
            <thead class="table-head">
                <tr>
                    <th class="table-cell-head">Produk</th>
                    <th class="table-cell-head">Kategori</th>
                    <th class="table-cell-head text-center">Stok</th>
                    <th class="table-cell-head text-center">Min Stok</th>
                    <th class="table-cell-head text-right">Nilai Stok</th>
                    <th class="table-cell-head">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($report['products'] as $product)
                <tr class="table-row">
                    <td class="table-cell">
                        <p class="font-medium text-white">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $product->code }}</p>
                    </td>
                    <td class="table-cell text-gray-300">{{ $product->category_name ?? '-' }}</td>
                    <td class="table-cell text-center font-semibold {{ $product->stock <= 0 ? 'text-red-400' : ($product->is_low_stock ? 'text-amber-400' : 'text-green-400') }}">
                        {{ number_format($product->stock) }} {{ $product->unit }}
                    </td>
                    <td class="table-cell text-center text-gray-400">{{ $product->min_stock }}</td>
                    <td class="table-cell text-right text-gray-300">Rp {{ number_format($product->stock * $product->buy_price, 0, ',', '.') }}</td>
                    <td class="table-cell">
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
    <x-table-pagination :paginator="$report['products']" />
</div>
@endsection
