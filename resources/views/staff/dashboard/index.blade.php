@extends('layouts.app')
@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Daftar tugas yang perlu diselesaikan')

@section('content')
<x-welcome-banner subtitle="Berikut adalah daftar tugas gudang yang perlu Anda selesaikan hari ini." />

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    {{-- Barang Masuk Menunggu --}}
    <div class="bg-gray-800/60 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5 bg-gray-900/20 flex items-center gap-3">
            <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.8)]"></div>
            <h3 class="font-semibold text-white">Barang Masuk — Perlu Dikonfirmasi</h3>
            <span class="ml-auto bg-green-900/40 text-green-400 text-xs font-medium px-2.5 py-1 rounded-full border border-green-500/20">
                {{ $pendingIn->count() }}
            </span>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($pendingIn as $tx)
            <div class="flex items-center gap-4 px-6 py-5 hover:bg-white/[0.02] transition-colors group">
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
                    <button class="px-4 py-2 bg-green-600/90 hover:bg-green-500 hover:shadow-[0_0_15px_rgba(34,197,94,0.4)] text-white text-xs font-semibold rounded-xl transition-all duration-300">
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
    <div class="bg-gray-800/60 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5 bg-gray-900/20 flex items-center gap-3">
            <div class="w-2.5 h-2.5 bg-red-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(248,113,113,0.8)]"></div>
            <h3 class="font-semibold text-white">Barang Keluar — Perlu Disiapkan</h3>
            <span class="ml-auto bg-red-900/40 text-red-400 text-xs font-medium px-2.5 py-1 rounded-full border border-red-500/20">
                {{ $pendingOut->count() }}
            </span>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($pendingOut as $tx)
            <div class="flex items-center gap-4 px-6 py-5 hover:bg-white/[0.02] transition-colors group">
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
                    <button class="px-4 py-2 bg-red-600/90 hover:bg-red-500 hover:shadow-[0_0_15px_rgba(239,68,68,0.4)] text-white text-xs font-semibold rounded-xl transition-all duration-300">
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
