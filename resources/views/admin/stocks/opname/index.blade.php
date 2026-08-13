@extends('layouts.app')
@section('title', 'Stock Opname')
@section('page-title', 'Stock Opname')
@section('page-subtitle', 'Bandingkan stok sistem dengan stok fisik')
@section('content')
@php 
    $role = auth()->user()->role;
    $prefix = match($role) {
        'Admin' => 'admin',
        'Manajer Gudang' => 'manager',
        default => 'staff',
    };
    $isStaff = $role === 'Staff Gudang';
@endphp

@if(!$isStaff)
<form id="opnameForm" action="{{ route("{$prefix}.stocks.opname.store") }}" method="POST">
    @csrf
</form>

    <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 mb-5">
        <div class="grid gap-4 lg:grid-cols-3">
            {{-- Kolom 1: Pencarian (GET Form) --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Pencarian</label>
                <form method="GET" action="{{ request()->url() }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari produk..."
                        class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                    <button type="submit" class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm rounded-lg transition-all">Cari</button>
                    @if(request('search'))
                    <a href="{{ request()->url() }}" class="px-3 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-lg transition-all" title="Reset">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                    @endif
                </form>
            </div>

            {{-- Kolom 2 & 3: Tanggal & Catatan --}}
            <div class="lg:col-span-2">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Tanggal Opname</label>
                        <input type="date" form="opnameForm" name="opname_date" value="{{ old('opname_date', date('Y-m-d')) }}" required class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Catatan <span class="text-red-500">*</span></label>
                        <input type="text" form="opnameForm" name="notes" value="{{ old('notes') }}" placeholder="Wajib diisi" required class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                        @error('notes')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="bg-blue-900/20 border border-blue-500/20 text-blue-400 p-4 rounded-xl mb-5 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm">Mode Lihat Saja. Anda hanya dapat melihat stok sistem saat ini.</span>
    </div>
@endif



    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-standard">
                <thead class="table-head">
                    <tr>
                        <th class="table-cell-head text-gray-400">Produk</th>
                        <th class="table-cell-head text-center text-gray-400">Stok Sistem</th>
                        @if(!$isStaff)
                        <th class="table-cell-head text-center text-gray-400">Stok Fisik</th>
                        <th class="table-cell-head text-center text-gray-400">Selisih</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @forelse($products as $product)
                    <tr class="table-row">
                        <td class="table-cell">
                            <p class="text-white font-medium">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                        </td>
                        <td class="table-cell text-center text-gray-300">{{ $product->stock }}</td>
                        @if(!$isStaff)
                        <td class="table-cell text-center">
                            <input type="number" form="opnameForm" name="stocks[{{ $product->id }}]" value="{{ old('stocks.' . $product->id, $product->stock) }}" min="0" max="4294967295" required data-system="{{ $product->stock }}" class="input-field w-24 text-center text-white rounded-lg px-2 py-1.5">
                        </td>
                        <td class="table-cell text-center text-gray-400" data-difference>0</td>
                        @endif
                    </tr>
                @empty
                    <x-table-empty :colspan="$isStaff ? 2 : 4" message="Belum ada produk." />
                @endforelse
                </tbody>
            </table>
        </div>
        <x-table-pagination :paginator="$products" />
    </div>

    @if($products->isNotEmpty() && !$isStaff)
    <div class="mt-6 flex justify-end">
        <button type="submit" form="opnameForm" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Simpan Penyesuaian
        </button>
    </div>
    @endif
    
@endsection
