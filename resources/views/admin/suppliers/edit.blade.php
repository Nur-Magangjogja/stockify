@extends('layouts.app')
@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Supplier <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Kontak</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $supplier->city) }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Alamat</label>
                    <textarea name="address" rows="2"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $supplier->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Website</label>
                    <input type="url" name="website" value="{{ old('website', $supplier->website) }}"
                        class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded border-gray-600 bg-gray-700">
                        <span class="text-sm text-gray-300">Supplier Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.suppliers.index') }}"
                   class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">Batal</a>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">Update Supplier</button>
            </div>
        </div>
    </form>
</div>
@endsection
