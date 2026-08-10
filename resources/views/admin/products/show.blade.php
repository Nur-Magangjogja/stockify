@extends('layouts.app')
@section('title', 'Detail Produk')
@section('page-title', $product->name)
@section('page-subtitle', 'Kode: ' . $product->code)

@section('content')
<div class="max-w-5xl">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ url()->previous() }}" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.products.edit', $product->id) }}"
           class="ml-auto flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Produk
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Left --}}
        <div class="space-y-4">
            {{-- Image --}}
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-4">
                @if($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                         class="w-full h-56 object-contain rounded-lg">
                @else
                    <div class="w-full h-56 bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Stock Status Card --}}
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-white mb-3">Status Stok</h3>
                <div class="text-center py-3">
                    <p class="text-4xl font-bold {{ $product->stock <= 0 ? 'text-red-400' : ($product->is_low_stock ? 'text-amber-400' : 'text-green-400') }}">
                        {{ number_format($product->stock) }}
                    </p>
                    <p class="text-gray-400 text-sm mt-1">{{ strtoupper($product->unit) }}</p>
                    <span class="mt-2 inline-block px-3 py-1 text-xs font-medium rounded-full
                        {{ $product->stock <= 0 ? 'bg-red-900/40 text-red-400' : ($product->is_low_stock ? 'bg-amber-900/40 text-amber-400' : 'bg-green-900/40 text-green-400') }}">
                        {{ $product->stock_status_label }}
                    </span>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-700 text-sm text-center text-gray-400">
                    Stok minimum: <span class="text-white font-medium">{{ $product->min_stock }} {{ $product->unit }}</span>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Main Info --}}
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Informasi Produk</h3>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-gray-400">Nama Produk</dt>
                        <dd class="text-sm font-medium text-white mt-0.5">{{ $product->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Kode SKU</dt>
                        <dd class="text-sm font-medium text-white mt-0.5 font-mono">{{ $product->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Kategori</dt>
                        <dd class="text-sm font-medium text-white mt-0.5">{{ $product->category->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Supplier</dt>
                        <dd class="text-sm font-medium text-white mt-0.5">{{ $product->supplier->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Harga Beli</dt>
                        <dd class="text-sm font-medium text-white mt-0.5">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Harga Jual</dt>
                        <dd class="text-sm font-medium text-white mt-0.5">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Status</dt>
                        <dd class="mt-0.5">
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $product->is_active ? 'bg-green-900/40 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                                {{ $product->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400">Ditambahkan</dt>
                        <dd class="text-sm text-white mt-0.5">{{ $product->created_at->format('d M Y') }}</dd>
                    </div>
                    @if($product->description)
                    <div class="col-span-2">
                        <dt class="text-xs text-gray-400">Deskripsi</dt>
                        <dd class="text-sm text-gray-300 mt-0.5">{{ $product->description }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Atribut --}}
            @if($product->attributes->count() > 0)
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Atribut Produk</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->attributes as $attr)
                    <div class="bg-gray-700 rounded-lg px-3 py-2 text-sm">
                        <span class="text-gray-400">{{ $attr->attribute_name }}:</span>
                        <span class="text-white font-medium ml-1">{{ $attr->attribute_value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Action Buttons --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <div class="flex gap-3">
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.stocks.in.create' : 'manager.stocks.in.create', ['product_id' => $product->id]) }}"
                   class="flex-1 py-2.5 text-center bg-green-700 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-all">
                    + Barang Masuk
                </a>
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.stocks.out.create' : 'manager.stocks.out.create', ['product_id' => $product->id]) }}"
                   class="flex-1 py-2.5 text-center bg-red-700 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all">
                    - Barang Keluar
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
