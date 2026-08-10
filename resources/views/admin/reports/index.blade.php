@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Pilih jenis laporan yang ingin dilihat')

@section('content')
@php $prefix = auth()->user()->isAdmin() ? 'admin' : 'manager'; @endphp
<div class="grid grid-cols-1 md:grid-cols-2 {{ auth()->user()->isAdmin() ? 'xl:grid-cols-3' : '' }} gap-4">
    <a href="{{ route("{$prefix}.reports.stock") }}"
       class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-blue-600 hover:bg-blue-900/10 transition-all group">
        <div class="w-12 h-12 bg-blue-600/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600/30 transition-all">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <h3 class="font-semibold text-white mb-1">Laporan Stok Barang</h3>
        <p class="text-sm text-gray-400">Lihat kondisi stok semua produk, produk menipis, nilai inventaris per kategori.</p>
        <span class="mt-4 inline-flex items-center text-xs text-blue-400 font-medium">Buka Laporan →</span>
    </a>

    <a href="{{ route("{$prefix}.reports.transactions") }}"
       class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-green-600 hover:bg-green-900/10 transition-all group">
        <div class="w-12 h-12 bg-green-600/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-600/30 transition-all">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="font-semibold text-white mb-1">Laporan Transaksi</h3>
        <p class="text-sm text-gray-400">Riwayat transaksi barang masuk dan keluar per periode waktu.</p>
        <span class="mt-4 inline-flex items-center text-xs text-green-400 font-medium">Buka Laporan →</span>
    </a>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.reports.activities') }}"
       class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-purple-600 hover:bg-purple-900/10 transition-all group">
        <div class="w-12 h-12 bg-purple-600/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600/30 transition-all">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <h3 class="font-semibold text-white mb-1">Laporan Aktivitas Pengguna</h3>
        <p class="text-sm text-gray-400">Audit log semua aktivitas pengguna dalam sistem.</p>
        <span class="mt-4 inline-flex items-center text-xs text-purple-400 font-medium">Buka Laporan →</span>
    </a>
    @endif
</div>
@endsection
