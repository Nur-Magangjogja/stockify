@extends('layouts.app')
@section('title', 'Manajemen Supplier')
@section('page-title', 'Supplier')
@section('page-subtitle', 'Kelola data supplier')

@section('content')
<div class="flex justify-between items-center mb-5">
    <span class="text-sm text-gray-400">{{ $suppliers->total() }} supplier</span>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.suppliers.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Supplier
    </a>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($suppliers as $supplier)
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="font-semibold text-white">{{ $supplier->name }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $supplier->city ?? 'Kota tidak diketahui' }}</p>
            </div>
            <span class="px-2 py-0.5 text-xs rounded-full {{ $supplier->is_active ? 'bg-green-900/40 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                {{ $supplier->is_active ? 'Aktif' : 'Non-aktif' }}
            </span>
        </div>

        <div class="space-y-1.5 text-sm">
            @if($supplier->contact_person)
            <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>{{ $supplier->contact_person }}</span>
            </div>
            @endif
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
        </div>

        <div class="mt-3 pt-3 border-t border-gray-700 flex items-center justify-between">
            <span class="text-xs text-gray-500">{{ $supplier->products_count }} produk</span>
            @if(auth()->user()->isAdmin())
            <div class="flex gap-1">
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                   class="p-1.5 text-gray-400 hover:text-amber-400 rounded-lg hover:bg-amber-900/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST"
                      onsubmit="return confirm('Hapus supplier {{ addslashes($supplier->name) }}?')">
                    @csrf @method('DELETE')
                    <button class="p-1.5 text-gray-400 hover:text-red-400 rounded-lg hover:bg-red-900/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-gray-500 text-sm">Belum ada supplier</div>
    @endforelse
</div>

@if($suppliers->hasPages())
<div class="mt-4">{{ $suppliers->links() }}</div>
@endif
@endsection
