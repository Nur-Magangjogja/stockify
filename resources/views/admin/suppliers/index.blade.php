@extends('layouts.app')
@section('title', 'Manajemen Supplier')
@section('page-title', 'Supplier')
@section('page-subtitle', 'Kelola data supplier')

@section('content')
<x-page-header
    :count="$suppliers->total()"
    count-label="supplier"
    :href="auth()->user()->isAdmin() ? route('admin.suppliers.create') : null"
    btn-label="Tambah Supplier"
/>

<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-5">
    <x-search-filter :action="request()->url()" placeholder="Cari nama supplier, nomor telepon, atau email..." :search="request('search')" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($suppliers as $supplier)
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
        <div class="flex items-start justify-between pb-3 mb-3 border-b border-gray-700">
            <div>
                <h3 class="font-semibold text-white">{{ $supplier->name }}</h3>
            </div>
            <span class="px-2 py-0.5 text-xs rounded-full bg-green-900/40 text-green-400">
                Aktif
            </span>
        </div>

        <div class="space-y-1.5 text-sm">

            @if($supplier->phone)
            <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span>{{ $supplier->phone }}</span>
            </div>
            @endif
            @if($supplier->email)
            <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="truncate">{{ $supplier->email }}</span>
            </div>
            @endif
            @if($supplier->address)
            <div class="flex items-start gap-2 text-gray-400 mt-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="line-clamp-2 text-xs" title="{{ $supplier->address }}">{{ $supplier->address }}</span>
            </div>
            @endif
        </div>

        <div class="mt-4 flex items-center justify-end">
            @if(auth()->user()->isAdmin())
            <div class="flex gap-1">
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                   class="p-1.5 text-gray-400 hover:text-amber-400 rounded-lg hover:bg-amber-900/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                @if($supplier->products()->count() == 0)
                <x-delete-button 
                    action="{{ route('admin.suppliers.destroy', $supplier->id) }}" 
                    message="Hapus supplier {{ addslashes($supplier->name) }}?" 
                    class="p-1.5 text-gray-400 hover:text-red-400 rounded-lg hover:bg-red-900/20 transition-all border-none bg-transparent" />
                @else
                <button type="button" class="p-1.5 text-gray-600 rounded-lg cursor-not-allowed bg-transparent" title="Tidak dapat dihapus karena masih memiliki {{ $supplier->products()->count() }} produk">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
                @endif
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-gray-500 text-sm">Belum ada supplier</div>
    @endforelse
</div>

<div>
    <x-table-pagination :paginator="$suppliers" />
</div>
@endsection
