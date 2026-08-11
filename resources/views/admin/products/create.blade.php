@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')
@section('page-subtitle', 'Lengkapi data produk di bawah ini')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Left: Main Info --}}
            <div class="lg:col-span-2 space-y-4">
                {{-- Informasi Dasar --}}
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Informasi Produk</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Produk <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                placeholder="Contoh: Laptop ASUS VivoBook 14">
                            @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Kode SKU</label>
                                <input type="text" name="code" value="{{ old('code') }}"
                                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Auto-generate jika kosong">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Satuan <span class="text-red-400">*</span></label>
                                <select name="unit" required class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach(['pcs', 'unit', 'kg', 'gram', 'liter', 'ml', 'box', 'karton', 'pak', 'lembar', 'meter', 'roll'] as $unit)
                                    <option value="{{ $unit }}" {{ old('unit') === $unit ? 'selected' : '' }}>{{ strtoupper($unit) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Harga & Stok --}}
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Harga & Stok</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div x-data="{ 
                            rawValue: '{{ old('buy_price', 0) }}',
                            displayValue: '0',
                            init() { this.updateDisplay(); },
                            updateDisplay() {
                                let num = parseInt(this.rawValue.toString().replace(/[^0-9]/g, '')) || 0;
                                this.displayValue = new Intl.NumberFormat('en-US').format(num);
                            },
                            onInput(e) {
                                let num = parseInt(e.target.value.replace(/[^0-9]/g, '')) || 0;
                                this.rawValue = num;
                                let oldLength = e.target.value.length;
                                let cursorOffset = oldLength - e.target.selectionStart;
                                this.displayValue = new Intl.NumberFormat('en-US').format(num);
                                this.$nextTick(() => {
                                    let newLength = this.displayValue.length;
                                    let pos = Math.max(0, newLength - cursorOffset);
                                    e.target.setSelectionRange(pos, pos);
                                });
                            }
                        }">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Harga Beli (Rp) <span class="text-red-400">*</span></label>
                            <input type="hidden" name="buy_price" :value="rawValue">
                            <div class="relative flex items-center bg-gray-900 border border-gray-600 rounded-lg focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500 overflow-hidden">
                                <span class="pl-3 text-gray-500 text-sm font-medium">Rp</span>
                                <input type="text" x-model="displayValue" @input="onInput" required
                                    class="w-full bg-transparent border-none text-gray-300 text-sm px-2 py-2.5 focus:ring-0">
                                <span class="pr-3 text-gray-500 text-sm">.00</span>
                            </div>
                        </div>
                        <div x-data="{ 
                            rawValue: '{{ old('sell_price', 0) }}',
                            displayValue: '0',
                            init() { this.updateDisplay(); },
                            updateDisplay() {
                                let num = parseInt(this.rawValue.toString().replace(/[^0-9]/g, '')) || 0;
                                this.displayValue = new Intl.NumberFormat('en-US').format(num);
                            },
                            onInput(e) {
                                let num = parseInt(e.target.value.replace(/[^0-9]/g, '')) || 0;
                                this.rawValue = num;
                                let oldLength = e.target.value.length;
                                let cursorOffset = oldLength - e.target.selectionStart;
                                this.displayValue = new Intl.NumberFormat('en-US').format(num);
                                this.$nextTick(() => {
                                    let newLength = this.displayValue.length;
                                    let pos = Math.max(0, newLength - cursorOffset);
                                    e.target.setSelectionRange(pos, pos);
                                });
                            }
                        }">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Harga Jual (Rp) <span class="text-red-400">*</span></label>
                            <input type="hidden" name="sell_price" :value="rawValue">
                            <div class="relative flex items-center bg-gray-900 border border-gray-600 rounded-lg focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500 overflow-hidden">
                                <span class="pl-3 text-gray-500 text-sm font-medium">Rp</span>
                                <input type="text" x-model="displayValue" @input="onInput" required
                                    class="w-full bg-transparent border-none text-gray-300 text-sm px-2 py-2.5 focus:ring-0">
                                <span class="pr-3 text-gray-500 text-sm">.00</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Stok Awal <span class="text-red-400">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Stok Minimum <span class="text-red-400">*</span></label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" min="0" required
                                class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Notifikasi ketika stok ≤ nilai ini</p>
                        </div>
                    </div>
                </div>

                {{-- Atribut --}}
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-700">
                        <h3 class="text-sm font-semibold text-white">Atribut Produk</h3>
                        <button type="button" id="add-attribute"
                            class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Atribut
                        </button>
                    </div>
                    <div id="attributes-container" class="space-y-3">
                        {{-- Atribut rows akan dimasukkan di sini --}}
                    </div>
                    <p class="text-xs text-gray-500 mt-2" id="no-attr-text">Belum ada atribut. Klik "Tambah Atribut" untuk menambahkan.</p>
                </div>
            </div>

            {{-- Right: Category, Supplier, Image --}}
            <div class="space-y-4">
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Klasifikasi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Kategori <span class="text-red-400">*</span></label>
                            <select name="category_id" required class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Supplier <span class="text-red-400">*</span></label>
                            <select name="supplier_id" required class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Gambar --}}
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5" x-data="{ imageUrl: 'https://placehold.co/600x400/374151/9ca3af?text=No+Image' }">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Gambar Produk</h3>
                    <label for="image"
                        class="border-2 border-dashed border-gray-600 rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 transition-colors block"
                        id="image-upload-area">
                        <img :src="imageUrl" alt="" class="w-full h-40 object-contain mb-3 rounded-lg">
                        <div id="upload-placeholder" class="hidden">
                            <svg class="w-10 h-10 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-400">Klik untuk upload gambar</p>
                            <p class="text-xs text-gray-600 mt-1">JPG, PNG — max 2MB</p>
                        </div>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg" class="hidden" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                    </label>
                </div>

                {{-- Status --}}
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-4 pb-3 border-b border-gray-700">Status</h3>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded border-gray-600 bg-gray-700">
                        <span class="text-sm text-gray-300">Produk Aktif</span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <a href="{{ route('admin.products.index') }}"
                       class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
                        Simpan Produk
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

