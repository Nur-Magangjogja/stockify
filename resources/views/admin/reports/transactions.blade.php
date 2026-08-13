@extends('layouts.app')
@section('title', 'Laporan Transaksi')
@section('page-title', 'Laporan Transaksi')

@section('content')
@php $prefix = auth()->user()->isAdmin() ? 'admin' : 'manager'; @endphp
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
    @foreach([
        ['label' => 'Total Masuk', 'value' => $report['total_in'] . ' trx', 'color' => 'text-green-400'],
        ['label' => 'Total Keluar', 'value' => $report['total_out'] . ' trx', 'color' => 'text-red-400'],
        ['label' => 'Qty Masuk', 'value' => number_format($report['total_in_qty']), 'color' => 'text-blue-400'],
        ['label' => 'Qty Keluar', 'value' => number_format($report['total_out_qty']), 'color' => 'text-amber-400'],
    ] as $stat)
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
        <p class="text-xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-5">
    <x-search-filter :action="request()->url()" :hideDefault="true">
        <div>
            <label class="block text-xs text-gray-400 mb-1">Tipe</label>
            <select name="type" class="input-field text-sm rounded-lg px-3 py-2">
                <option value="">Semua</option>
                <option value="in" {{ ($filters['type'] ?? '') === 'in' ? 'selected' : '' }}>Masuk</option>
                <option value="out" {{ ($filters['type'] ?? '') === 'out' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Dari</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                class="input-field text-sm rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Sampai</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                class="input-field text-sm rounded-lg px-3 py-2">
        </div>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" target="_blank"
           class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-all w-full sm:w-auto h-[38px] self-end mb-[2px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Excel
        </a>
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
                    <th class="table-cell-head">Petugas</th>
                    <th class="table-cell-head">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($report['transactions'] as $tx)
                <tr class="table-row">
                    <td class="table-cell font-mono text-xs text-gray-300">#{{ $tx->id }}</td>
                    <td class="table-cell">
                        <p class="text-white">{{ $tx->product_name }}</p>
                        <p class="text-xs text-gray-400">{{ $tx->product_sku }}</p>
                    </td>
                    <td class="table-cell">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $tx->type === 'Masuk' ? 'stock-badge-in' : 'stock-badge-out' }}">
                            {{ $tx->type }}
                        </span>
                    </td>
                    <td class="table-cell text-center font-semibold {{ $tx->type === 'Masuk' ? 'text-green-400' : 'text-red-400' }}">
                        {{ $tx->type === 'Masuk' ? '+' : '-' }}{{ number_format($tx->quantity) }}
                    </td>
                    <td class="table-cell text-gray-300">{{ $tx->user_name }}</td>
                    <td class="table-cell text-gray-400">{{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}</td>
                </tr>
                @empty
                <x-table-empty :colspan="6" message="Tidak ada data transaksi" />
                @endforelse
            </tbody>
        </table>
    </div>
    <x-table-pagination :paginator="$report['transactions']" />
</div>
@endsection
