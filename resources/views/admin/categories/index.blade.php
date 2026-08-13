@extends('layouts.app')
@section('title', 'Manajemen Kategori')
@section('page-title', 'Kategori Produk')
@section('page-subtitle', 'Kelola kategori produk')

@section('content')
<x-page-header
    :count="$categories->total()"
    count-label="kategori"
    :href="route('admin.categories.create')"
    btn-label="Tambah Kategori"
/>

{{-- Search Filter --}}
<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-6">
    <x-search-filter :action="request()->url()" :hideDefault="true">
        <div class="flex-1 w-full md:w-auto">
            <input type="text" name="search_category" value="{{ request('search_category') }}"
                placeholder="Cari Kategori..."
                class="input-field w-full text-sm rounded-lg px-3 py-2.5">
        </div>
        <div class="flex-1 w-full md:w-auto">
            <input type="text" name="search_product" value="{{ request('search_product') }}"
                placeholder="Cari Produk di dalam kategori..."
                class="input-field w-full text-sm rounded-lg px-3 py-2.5">
        </div>
    </x-search-filter>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
    @forelse($categories as $category)
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg overflow-hidden flex flex-col">
        {{-- Header Kategori --}}
        <div class="p-5 border-b border-gray-700 flex justify-between items-start bg-gray-800/50">
            <div class="flex-1 min-w-0 pr-4">
                <h3 class="font-bold text-lg text-white mb-1 truncate">{{ $category->name }}</h3>
                @if($category->description)
                <p class="text-sm text-gray-400 break-words line-clamp-2">{{ $category->description }}</p>
                @endif
            </div>
            
            {{-- Aksi Edit / Hapus --}}
            <div class="flex items-center gap-1">
                <a href="{{ route('admin.categories.edit', $category->id) }}"
                   class="p-1.5 text-gray-400 hover:text-amber-400 rounded-lg hover:bg-amber-900/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                @if($category->products->count() == 0)
                <x-delete-button 
                    action="{{ route('admin.categories.destroy', $category->id) }}" 
                    message="Hapus kategori {{ addslashes($category->name) }}?" 
                    class="p-1.5 text-gray-400 hover:text-red-400 rounded-lg hover:bg-red-900/20 transition-all border-none bg-transparent" />
                @else
                <button type="button" class="p-1.5 text-gray-600 rounded-lg cursor-not-allowed bg-transparent" title="Tidak dapat dihapus karena masih memiliki {{ $category->products->count() }} produk terkait">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
                @endif
            </div>
        </div>

        {{-- Body - List Produk --}}
        <div class="p-5 flex-1 bg-gray-900/20">
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Produk Terkait ({{ $category->products->count() }})</h4>
            @if($category->products->count() > 0)
            <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                @foreach($category->products as $product)
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-700 text-gray-200 border border-gray-600 max-w-full truncate" title="{{ $product->name }}">
                    {{ $product->name }}
                </span>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500 italic">Belum ada produk</p>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-8 text-center">
            <p class="text-gray-400">Belum ada kategori</p>
        </div>
    </div>
    @endforelse
</div>

<div>
    <x-table-pagination :paginator="$categories" />
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(31, 41, 55, 0.5); 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(75, 85, 99, 1); 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(107, 114, 128, 1); 
}
</style>
@endsection
