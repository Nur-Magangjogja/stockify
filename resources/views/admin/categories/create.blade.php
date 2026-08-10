@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="max-w-lg">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Kategori <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                    placeholder="Nama kategori">
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Deskripsi kategori (opsional)">{{ old('description') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.categories.index') }}"
                   class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
