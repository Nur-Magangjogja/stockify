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
                        data-price="{{ $product->sell_price }}"
                        {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}>
                        [{{ $product->code }}] {{ $product->name }} — Stok: {{ $product->stock }} {{ $product->unit }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Info Produk --}}
            <div id="product-info" class="hidden bg-amber-900/20 border border-amber-700/50 rounded-lg p-3 text-sm">
                <p class="text-amber-300">Stok tersedia: <span id="current-stock" class="font-bold text-white"></span></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Jumlah <span class="text-red-400">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required id="quantity-input"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Harga Jual (Rp)</label>
                    <input type="number" name="unit_price" value="{{ old('unit_price') }}" min="0" id="unit-price"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">No. Referensi</label>
                    <input type="text" name="reference_number" value="{{ old('reference_number') }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="No. pesanan / faktur (opsional)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Tanggal Transaksi <span class="text-red-400">*</span></label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Catatan</label>
                <textarea name="notes" rows="2"
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Tujuan / catatan pengeluaran (opsional)">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route("{$prefix}.stocks.index") }}"
                   class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">Batal</a>
                <button type="submit"
                    class="flex-1 py-2.5 bg-red-700 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all">
                    Catat Barang Keluar
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const select = document.getElementById('product-select');
const infoBox = document.getElementById('product-info');
const currentStockEl = document.getElementById('current-stock');
const unitPriceEl = document.getElementById('unit-price');
const qtyInput = document.getElementById('quantity-input');

select.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (this.value) {
        const stock = parseInt(opt.dataset.stock);
        currentStockEl.textContent = stock + ' ' + opt.dataset.unit;
        if (!unitPriceEl.value) unitPriceEl.value = opt.dataset.price;
        qtyInput.max = stock;
        infoBox.classList.remove('hidden');
    } else {
        infoBox.classList.add('hidden');
        qtyInput.removeAttribute('max');
    }
});
if (select.value) select.dispatchEvent(new Event('change'));
</script>
@endpush
