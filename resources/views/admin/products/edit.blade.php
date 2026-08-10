@extends('layouts.app')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-subtitle', $product->name)

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Informasi Produk</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Produk <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                            @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Kode SKU</label>
                                <input type="text" name="code" value="{{ old('code', $product->code) }}"
                                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Satuan <span class="text-red-400">*</span></label>
                                <select name="unit" required class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach(['pcs', 'unit', 'kg', 'gram', 'liter', 'ml', 'box', 'karton', 'pak', 'lembar', 'meter', 'roll'] as $unit)
                                    <option value="{{ $unit }}" {{ old('unit', $product->unit) === $unit ? 'selected' : '' }}>{{ strtoupper($unit) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Harga & Stok</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Harga Beli (Rp) <span class="text-red-400">*</span></label>
                            <input type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" min="0" step="100" required
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Harga Jual (Rp) <span class="text-red-400">*</span></label>
                            <input type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" min="0" step="100" required
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">
                                Stok Saat Ini
                                <span class="text-gray-500 font-normal">(ubah via transaksi)</span>
                            </label>
                            <input type="number" value="{{ $product->stock }}" disabled
                                class="w-full bg-gray-900/50 border border-gray-700 text-gray-500 text-sm rounded-lg px-3 py-2.5 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Stok Minimum <span class="text-red-400">*</span></label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" required
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Atribut --}}
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-700">
                        <h3 class="text-sm font-semibold text-white">Atribut Produk</h3>
                        <button type="button" id="add-attribute" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Atribut
                        </button>
                    </div>
                    <div id="attributes-container" class="space-y-3">
                        @foreach($product->attributes as $i => $attr)
                        <div class="flex gap-2 items-center">
                            <input type="hidden" name="attributes[{{ $i }}][id]" value="{{ $attr->id }}">
                            <input type="text" name="attributes[{{ $i }}][attribute_name]" value="{{ $attr->attribute_name }}"
                                placeholder="Nama"
                                class="flex-1 bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <input type="text" name="attributes[{{ $i }}][attribute_value]" value="{{ $attr->attribute_value }}"
                                placeholder="Nilai"
                                class="flex-1 bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="p-2 text-red-400 hover:bg-red-900/20 rounded-lg transition-all flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @if($product->attributes->isEmpty())
                    <p class="text-xs text-gray-500 mt-2" id="no-attr-text">Belum ada atribut.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Klasifikasi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Kategori <span class="text-red-400">*</span></label>
                            <select name="category_id" required class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Supplier</label>
                            <select name="supplier_id" class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Tanpa Supplier --</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Gambar Produk</h3>
                    <div class="border-2 border-dashed border-gray-600 rounded-xl p-4 text-center cursor-pointer hover:border-blue-500 transition-colors"
                         onclick="document.getElementById('image').click()">
                        <img id="image-preview"
                             src="{{ $product->image ? $product->image_url : '' }}"
                             alt="" class="{{ $product->image ? 'w-full h-32 object-contain rounded-lg mb-2' : 'hidden' }}">
                        <div id="upload-placeholder" class="{{ $product->image ? 'hidden' : '' }}">
                            <svg class="w-8 h-8 text-gray-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-gray-400">Klik untuk ganti gambar</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG — max 2MB</p>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Status</h3>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded border-gray-600 bg-gray-700">
                        <span class="text-sm text-gray-300">Produk Aktif</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.products.index') }}"
                       class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">Batal</a>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let attrIndex = {{ $product->attributes->count() }};
const container = document.getElementById('attributes-container');

document.getElementById('add-attribute').addEventListener('click', function() {
    const noAttr = document.getElementById('no-attr-text');
    if (noAttr) noAttr.classList.add('hidden');
    const row = document.createElement('div');
    row.className = 'flex gap-2 items-center';
    row.innerHTML = `
        <input type="text" name="attributes[${attrIndex}][attribute_name]"
            placeholder="Nama" class="flex-1 bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
        <input type="text" name="attributes[${attrIndex}][attribute_value]"
            placeholder="Nilai" class="flex-1 bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="button" onclick="this.parentElement.remove()"
            class="p-2 text-red-400 hover:bg-red-900/20 rounded-lg flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>`;
    container.appendChild(row);
    attrIndex++;
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('image-preview');
            img.src = e.target.result;
            img.className = 'w-full h-32 object-contain rounded-lg mb-2';
            document.getElementById('upload-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
