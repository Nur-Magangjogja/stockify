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
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-400 mb-1">Tipe</label>
            <select name="type" class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2">
                <option value="">Semua</option>
                <option value="in" {{ ($filters['type'] ?? '') === 'in' ? 'selected' : '' }}>Masuk</option>
                <option value="out" {{ ($filters['type'] ?? '') === 'out' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Dari</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Sampai</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">Tampilkan</button>
        <a href="?{{ http_build_query(array_merge($filters ?? [], ['export' => 'pdf'])) }}"
           class="px-4 py-2 bg-red-700 hover:bg-red-600 text-white text-sm rounded-lg flex items-center gap-1.5">
            Export PDF
        </a>
    </form>
</div>

<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="border-b border-gray-700">
                <tr>
                    <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase">Referensi</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase">Produk</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase">Tipe</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase text-center">Qty</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase">Petugas</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($report['transactions'] as $tx)
                <tr class="hover:bg-gray-750 transition-colors">
                    <td class="px-5 py-3.5 font-mono text-xs text-gray-300">#{{ $tx->id }}</td>
                    <td class="px-4 py-3.5">
                        <p class="text-white">{{ $tx->product_name }}</p>
                        <p class="text-xs text-gray-400">{{ $tx->product_sku }}</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $tx->type === 'Masuk' ? 'stock-badge-in' : 'stock-badge-out' }}">
                            {{ $tx->type }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center font-semibold {{ $tx->type === 'Masuk' ? 'text-green-400' : 'text-red-400' }}">
                        {{ $tx->type === 'Masuk' ? '+' : '-' }}{{ number_format($tx->quantity) }}
                    </td>
                    <td class="px-4 py-3.5 text-gray-300">{{ $tx->user_name }}</td>
                    <td class="px-4 py-3.5 text-gray-400">{{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-500">Tidak ada data transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
