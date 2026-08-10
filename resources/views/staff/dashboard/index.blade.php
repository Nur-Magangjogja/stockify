@extends('layouts.app')
@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Daftar tugas yang perlu diselesaikan')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
    {{-- Barang Masuk Menunggu --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl">
        <div class="px-5 py-4 border-b border-gray-700 flex items-center gap-2">
            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
            <h3 class="font-semibold text-white">Barang Masuk — Perlu Dikonfirmasi</h3>
            <span class="ml-auto bg-green-900/40 text-green-400 text-xs font-medium px-2 py-0.5 rounded-full">
                {{ $pendingIn->count() }}
            </span>
        </div>
        <div class="divide-y divide-gray-700">
            @forelse($pendingIn as $tx)
            <div class="flex items-center gap-3 px-5 py-3.5">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $tx->product->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Qty: <span class="text-green-400 font-semibold">+{{ $tx->quantity }}</span> {{ $tx->product->unit }}
                        @if($tx->reference_number)
                        · {{ $tx->reference_number }}
                        @endif
                    </p>
                </div>
                <form action="{{ route('staff.stocks.confirm-in', $tx->id) }}" method="POST">
                    @csrf
                    <button class="px-3 py-1.5 bg-green-700 hover:bg-green-600 text-white text-xs font-medium rounded-lg transition-all">
                        Konfirmasi
                    </button>
                </form>
            </div>
            @empty
            <div class="px-5 py-10 text-center">
                <p class="text-gray-500 text-sm">Tidak ada barang masuk yang menunggu konfirmasi</p>
                <span class="text-2xl mt-2 block">✅</span>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Barang Keluar Menunggu --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl">
        <div class="px-5 py-4 border-b border-gray-700 flex items-center gap-2">
            <div class="w-2 h-2 bg-red-400 rounded-full"></div>
            <h3 class="font-semibold text-white">Barang Keluar — Perlu Disiapkan</h3>
            <span class="ml-auto bg-red-900/40 text-red-400 text-xs font-medium px-2 py-0.5 rounded-full">
                {{ $pendingOut->count() }}
            </span>
        </div>
        <div class="divide-y divide-gray-700">
            @forelse($pendingOut as $tx)
            <div class="flex items-center gap-3 px-5 py-3.5">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $tx->product->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Qty: <span class="text-red-400 font-semibold">-{{ $tx->quantity }}</span> {{ $tx->product->unit }}
                        @if($tx->reference_number)
                        · {{ $tx->reference_number }}
                        @endif
                    </p>
                </div>
                <form action="{{ route('staff.stocks.confirm-out', $tx->id) }}" method="POST">
                    @csrf
                    <button class="px-3 py-1.5 bg-red-700 hover:bg-red-600 text-white text-xs font-medium rounded-lg transition-all">
                        Konfirmasi
                    </button>
                </form>
            </div>
            @empty
            <div class="px-5 py-10 text-center">
                <p class="text-gray-500 text-sm">Tidak ada barang keluar yang menunggu konfirmasi</p>
                <span class="text-2xl mt-2 block">✅</span>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
