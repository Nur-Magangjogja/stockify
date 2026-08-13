@extends('layouts.app')
@section('title', 'Riwayat Transaksi Stok')
@section('page-title', 'Riwayat Transaksi')
@section('page-subtitle', 'Barang masuk dan keluar')

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
<div class="flex flex-col sm:flex-row flex-wrap gap-3 mb-5">
    <a href="{{ route("{$prefix}.stocks.in.create") }}"
       class="flex-1 sm:flex-none justify-center items-center gap-2 px-4 py-2.5 bg-green-700 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-all flex">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"/>
        </svg>
        <span class="whitespace-nowrap">Barang Masuk</span>
    </a>
    <a href="{{ route("{$prefix}.stocks.out.create") }}"
       class="flex-1 sm:flex-none justify-center items-center gap-2 px-4 py-2.5 bg-red-700 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all flex">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8V20m0 0l4-4m-4 4l-4-4"/>
        </svg>
        <span class="whitespace-nowrap">Barang Keluar</span>
    </a>
    <a href="{{ route("{$prefix}.stocks.opname.index") }}"
       class="flex-1 sm:flex-none justify-center items-center gap-2 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all flex">
        <span class="whitespace-nowrap">Stock Opname</span>
    </a>
</div>
@endif

{{-- Filter --}}
<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-5">
    <x-search-filter :action="request()->url()" placeholder="Cari no. referensi / produk..." :search="$filters['search'] ?? ''">
        <select name="type" class="input-field w-full md:w-auto text-sm rounded-lg px-3 py-2.5">
            <option value="">Semua Tipe</option>
            <option value="in" {{ ($filters['type'] ?? '') === 'in' ? 'selected' : '' }}>Barang Masuk</option>
            <option value="out" {{ ($filters['type'] ?? '') === 'out' ? 'selected' : '' }}>Barang Keluar</option>
        </select>
        <select name="status" class="input-field w-full md:w-auto text-sm rounded-lg px-3 py-2.5">
            <option value="">Semua Status</option>
            <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="confirmed" {{ ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="cancelled" {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                class="input-field w-full sm:flex-1 text-sm rounded-lg px-3 py-2.5">
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                class="input-field w-full sm:flex-1 text-sm rounded-lg px-3 py-2.5">
        </div>
    </x-search-filter>
</div>

<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-standard">
            <thead class="table-head">
                <tr>
                    <th class="table-cell-head">Referensi</th>
                    <th class="table-cell-head">Produk</th>
                    <th class="table-cell-head">Tipe</th>
                    <th class="table-cell-head text-center">Qty</th>
                    <th class="table-cell-head hidden lg:table-cell">Stok</th>
                    <th class="table-cell-head">Status</th>
                    <th class="table-cell-head hidden md:table-cell">Tanggal</th>
                    @if(!$isStaff)
                    <th class="table-cell-head text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($transactions as $tx)
                <tr class="table-row">
                    <td class="table-cell">
                        <p class="font-mono text-xs text-gray-300">{{ $tx->reference_number }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $tx->product->code }}</p>
                    </td>
                    <td class="table-cell">
                        <p class="text-white font-medium truncate max-w-xs">{{ $tx->product->name }}</p>
                    </td>
                    <td class="table-cell">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $tx->type === 'Masuk' ? 'stock-badge-in' : 'stock-badge-out' }}">
                            {{ $tx->type_label }}
                        </span>
                    </td>
                    <td class="table-cell text-center">
                        <span class="font-bold {{ $tx->type === 'Masuk' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $tx->type === 'Masuk' ? '+' : '-' }}{{ number_format($tx->quantity) }}
                        </span>
                    </td>
                    <td class="table-cell hidden lg:table-cell text-xs text-gray-400">
                        {{ number_format($tx->stock_before) }} → {{ number_format($tx->stock_after) }}
                    </td>
                    <td class="table-cell">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                     {{ $tx->status === 'Diterima' ? 'bg-green-900/40 text-green-400' :
                                         ($tx->status === 'Pending' ? 'bg-amber-900/40 text-amber-400' : 'bg-red-900/40 text-red-400') }}">
                            {{ $tx->status_label }}
                        </span>
                    </td>
                    <td class="table-cell hidden md:table-cell text-sm text-gray-400">
                        {{ $tx->transaction_date->format('d M Y') }}
                    </td>
                    @if(!$isStaff)
                    <td class="table-cell text-right">
                        @if($tx->status === 'Pending')
                        <form action="{{ route("{$prefix}.stocks.confirm", $tx->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-2.5 py-1 bg-green-700 hover:bg-green-600 text-white text-xs rounded-lg transition-all">
                                Konfirmasi
                            </button>
                        </form>
                        @if(auth()->user()->isAdmin())
                            <x-delete-button 
                                action="{{ route('admin.stocks.cancel', $tx->id) }}" 
                                message="Batalkan transaksi ini?" 
                                method="POST"
                                :hideIcon="true"
                                class="px-2.5 py-1 bg-red-900/40 hover:bg-red-700 text-red-400 text-xs rounded-lg transition-all border-none bg-transparent">
                                Batal
                            </x-delete-button>
                        @endif
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <x-table-empty :colspan="$isStaff ? 7 : 8" message="Tidak ada transaksi ditemukan" />
                @endforelse
            </tbody>
        </table>
    </div>
    <x-table-pagination :paginator="$transactions" />
</div>
@endsection
