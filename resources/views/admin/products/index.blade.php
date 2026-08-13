@extends('layouts.app')
@section('title', 'Daftar Produk')
@section('page-title', 'Produk')
@section('page-subtitle', 'Manajemen data produk')

@section('content')
@php $prefix = auth()->user()->isAdmin() ? 'admin' : 'manager'; @endphp
{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
    <div class="flex items-center gap-2">
        <span class="text-sm text-gray-400">{{ $products->total() }} produk ditemukan</span>
    </div>
    <div class="flex flex-col sm:flex-row flex-wrap gap-2 w-full md:w-auto">
        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
           class="flex-1 sm:flex-none justify-center items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all flex">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span class="whitespace-nowrap">Import Excel</span>
        </button>
        <a href="{{ route("{$prefix}.products.create") }}"
           class="flex-1 sm:flex-none justify-center items-center gap-2 px-4 py-2.5 btn-primary text-white text-sm font-medium rounded-lg flex">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="whitespace-nowrap">Tambah Produk</span>
        </a>
    </div>
</div>

{{-- Import Modal --}}
<div id="importModal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-xl border border-gray-700 max-w-md w-full shadow-2xl">
        <div class="flex justify-between items-center p-5 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">Import Produk</h3>
            <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route("{$prefix}.products.import") }}" method="POST" enctype="multipart/form-data" class="p-5">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">Upload File Excel (.xlsx, .xls, .csv)</label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                    class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600 bg-gray-900 rounded-lg border border-gray-700">
            </div>
            <div class="text-xs text-gray-400 mb-5">
                <p>Kolom wajib (baris 1): nama_produk, sku, id_kategori, id_supplier, harga_beli, harga_jual.</p>
                <p>Kolom opsional: deskripsi, minimum_stok.</p>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">Import Sekarang</button>
            </div>
        </form>
    </div>
</div>

{{-- Filter --}}
<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-5">
    <x-search-filter :action="request()->url()" placeholder="Cari nama, kode produk..." :search="$filters['search'] ?? ''">
        <select name="category_id" class="input-field w-full md:w-auto text-sm rounded-lg px-3 py-2.5">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        <select name="stock_status" class="input-field w-full md:w-auto text-sm rounded-lg px-3 py-2.5">
            <option value="">Semua Status Stok</option>
            <option value="low" {{ ($filters['stock_status'] ?? '') === 'low' ? 'selected' : '' }}>Stok Menipis</option>
            <option value="out" {{ ($filters['stock_status'] ?? '') === 'out' ? 'selected' : '' }}>Stok Habis</option>
        </select>
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
                    <th class="table-cell-head">Stok</th>
                    <th class="table-cell-head hidden lg:table-cell">Harga Beli</th>
                    <th class="table-cell-head hidden lg:table-cell">Harga Jual</th>
                    <th class="table-cell-head">Status</th>
                    <th class="table-cell-head text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($products as $product)
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-700 flex-shrink-0 overflow-hidden">
                                <img src="{{ $product->image ? $product->image_url : 'https://placehold.co/600x400/374151/9ca3af?text=No+Image' }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="table-cell">
                        <span class="text-gray-300 text-sm">{{ $product->category->name ?? '-' }}</span>
                    </td>
                    <td class="table-cell">
                        <div>
                            <span class="font-semibold {{ $product->stock <= 0 ? 'text-red-400' : ($product->is_low_stock ? 'text-amber-400' : 'text-green-400') }}">
                                {{ number_format($product->stock) }} {{ $product->unit }}
                            </span>
                            @if($product->is_low_stock && $product->stock > 0)
                            <span class="ml-1.5 text-xs bg-amber-900/50 text-amber-400 px-1.5 py-0.5 rounded-full">min:{{ $product->min_stock }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="table-cell hidden lg:table-cell">
                        <span class="text-gray-300">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</span>
                    </td>
                    <td class="table-cell hidden lg:table-cell">
                        <span class="text-gray-300">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                    </td>
                    <td class="table-cell">
                        <x-stock-badge
                            :stock="$product->stock"
                            :min-stock="$product->min_stock"
                        />
                    </td>
                    <td class="table-cell text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route(auth()->user()->isAdmin() ? 'admin.products.show' : 'manager.products.show', $product->id) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-400 rounded-lg hover:bg-blue-900/20 transition-all"
                               title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 7c-3.866 0-7-2.686-7-6s3.134-6 7-6 7 2.686 7 6-3.134 6-7 6z"/>
                                </svg>
                            </a>
                            <a href="{{ route("{$prefix}.products.edit", $product->id) }}"
                               class="p-1.5 text-gray-400 hover:text-amber-400 rounded-lg hover:bg-amber-900/20 transition-all"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <x-delete-button 
                                action="{{ route($prefix . '.products.destroy', $product->id) }}" 
                                message="Hapus produk {{ addslashes($product->name) }}?" 
                                class="p-1.5 text-gray-400 hover:text-red-400 rounded-lg hover:bg-red-900/20 transition-all border-none bg-transparent" />
                        </div>
                    </td>
                </tr>
                @empty
                <x-table-empty :colspan="7" message="Tidak ada produk ditemukan">
                    <x-slot:sub>Coba ubah filter pencarian atau tambah produk baru</x-slot:sub>
                </x-table-empty>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-table-pagination :paginator="$products" />
</div>
@endsection
