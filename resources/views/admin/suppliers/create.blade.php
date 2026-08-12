@extends('layouts.app')
@section('title', 'Tambah Supplier')
@section('page-title', 'Tambah Supplier Baru')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.suppliers.store') }}" method="POST">
        @csrf
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Supplier <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="email@supplier.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="021-5551234">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Jakarta">
                </div>
                <div class="flex items-end pb-1">
                    <!-- Checkbox is_active dihapus karena tidak ada di database supplier -->
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.suppliers.index') }}"
                   class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">Batal</a>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">Simpan Supplier</button>
            </div>
        </div>
    </form>
</div>
@endsection
