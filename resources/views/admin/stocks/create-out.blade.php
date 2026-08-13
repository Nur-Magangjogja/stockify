@extends('layouts.app')
@section('title', 'Barang Keluar')
@section('page-title', 'Catat Barang Keluar')
@section('page-subtitle', 'Rekam transaksi pengeluaran barang')

@section('content')
<div class="max-w-2xl">
    @php $prefix = auth()->user()->isAdmin() ? 'admin' : 'manager'; @endphp
    <form action="{{ route("{$prefix}.stocks.out.store") }}" method="POST">
        @csrf
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Produk <span class="text-red-400">*</span></label>
                <select name="product_id" required id="product-select"
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        data-stock="{{ $product->stock }}" data-unit="{{ $product->unit }}"
                        data-price="{{ $product->sell_price }}" data-sku="{{ $product->code }}"
                        {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}
                        {{ $product->has_pending_transaction ? 'disabled' : '' }}>
                        [{{ $product->code }}] {{ $product->name }} — Stok: {{ $product->stock }} {{ $product->unit }}
                        {{ $product->has_pending_transaction ? '(Pending)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Form fields shown only after product selection --}}
            <div id="transaction-form" class="space-y-4">
                {{-- Info Produk --}}
            <div id="product-info" class="hidden bg-amber-900/20 border border-amber-700/50 rounded-lg p-3 text-sm">
                <p class="text-amber-300">Stok tersedia: <span id="current-stock" class="font-bold text-white"></span></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Jumlah <span class="text-red-400">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required id="quantity-input" placeholder="0"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Harga Jual (Rp) <span class="text-xs text-gray-500 font-normal">(Otomatis)</span></label>
                    <input type="hidden" name="unit_price" id="unit-price-hidden" value="{{ old('unit_price') }}">
                    <input type="text" id="unit-price-display" value="{{ old('unit_price') }}" readonly
                        class="w-full bg-gray-800 border border-gray-700 text-gray-400 text-sm rounded-lg px-3 py-2.5 cursor-not-allowed">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">No. Referensi</label>
                    <input type="text" name="reference_number" id="reference-input" value="{{ old('reference_number') }}" readonly
                        class="w-full bg-gray-800 border border-gray-700 text-gray-400 text-sm rounded-lg px-3 py-2.5 cursor-not-allowed pointer-events-none"
                        placeholder="Auto-generate sesuai SKU">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Tanggal Transaksi <span class="text-red-400">*</span></label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required readonly
                        class="w-full bg-gray-800 border border-gray-700 text-gray-400 text-sm rounded-lg px-3 py-2.5 cursor-not-allowed pointer-events-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Catatan</label>
                <textarea name="notes" rows="2"
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Tujuan / catatan pengeluaran (opsional)">{{ old('notes') }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ route("{$prefix}.stocks.index") }}"
                   class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">Batal</a>
                <button type="submit"
                    class="flex-1 py-2.5 bg-red-700 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all">
                    Catat Barang Keluar
                </button>
            </div>
            </div>
        </div>
    </form>
</div>
@endsection



