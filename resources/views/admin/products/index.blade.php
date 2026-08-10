@extends('layouts.app')
@section('title', 'Daftar Produk')
@section('page-title', 'Produk')
@section('page-subtitle', 'Manajemen data produk')

@section('content')
{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div class="flex items-center gap-2">
        <span class="text-sm text-gray-400">{{ $products->total() }} produk ditemukan</span>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.products.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </a>
    @endif
</div>

{{-- Filter --}}
<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-5">
    <form method="GET" action="{{ request()->url() }}" class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-48">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                placeholder="Cari nama, kode produk..."
                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <select name="category_id" class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        <select name="stock_status" class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Semua Status Stok</option>
            <option value="low" {{ ($filters['stock_status'] ?? '') === 'low' ? 'selected' : '' }}>Stok Menipis</option>
            <option value="out" {{ ($filters['stock_status'] ?? '') === 'out' ? 'selected' : '' }}>Stok Habis</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-all">Filter</button>
        @if(array_filter($filters))
        <a href="{{ request()->url() }}" class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-lg transition-all">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-750 border-b border-gray-700">
                    <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Produk</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Kategori</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Stok</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide hidden lg:table-cell">Harga Beli</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide hidden lg:table-cell">Harga Jual</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($products as $product)
                <tr class="hover:bg-gray-750 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-700 flex-shrink-0 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="text-gray-300 text-sm">{{ $product->category->name ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-4">
                        <div>
                            <span class="font-semibold {{ $product->stock <= 0 ? 'text-red-400' : ($product->is_low_stock ? 'text-amber-400' : 'text-green-400') }}">
                                {{ number_format($product->stock) }} {{ $product->unit }}
                            </span>
                            @if($product->is_low_stock && $product->stock > 0)
                            <span class="ml-1.5 text-xs bg-amber-900/50 text-amber-400 px-1.5 py-0.5 rounded-full">min:{{ $product->min_stock }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-4 hidden lg:table-cell">
                        <span class="text-gray-300">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-4 py-4 hidden lg:table-cell">
                        <span class="text-gray-300">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-4 py-4">
                        @if($product->stock <= 0)
                            <span class="px-2.5 py-1 bg-red-900/40 text-red-400 text-xs font-medium rounded-full">Habis</span>
                        @elseif($product->is_low_stock)
                            <span class="px-2.5 py-1 bg-amber-900/40 text-amber-400 text-xs font-medium rounded-full">Menipis</span>
                        @else
                            <span class="px-2.5 py-1 bg-green-900/40 text-green-400 text-xs font-medium rounded-full">Tersedia</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route(auth()->user()->isAdmin() ? 'admin.products.show' : 'manager.products.show', $product->id) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-400 rounded-lg hover:bg-blue-900/20 transition-all"
                               title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 7c-3.866 0-7-2.686-7-6s3.134-6 7-6 7 2.686 7 6-3.134 6-7 6z"/>
                                </svg>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="p-1.5 text-gray-400 hover:text-amber-400 rounded-lg hover:bg-amber-900/20 transition-all"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-400 rounded-lg hover:bg-red-900/20 transition-all" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <div class="text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-sm font-medium">Tidak ada produk ditemukan</p>
                            <p class="text-xs mt-1">Coba ubah filter pencarian atau tambah produk baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="px-5 py-4 border-t border-gray-700">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
