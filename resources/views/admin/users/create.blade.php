@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-lg">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Role <span class="text-red-400">*</span></label>
                <select name="role" required class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="warehouse_manager" {{ old('role') === 'warehouse_manager' ? 'selected' : '' }}>Manajer Gudang</option>
                    <option value="warehouse_staff" {{ old('role') === 'warehouse_staff' ? 'selected' : '' }}>Staff Gudang</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Password <span class="text-red-400">*</span></label>
                <input type="password" name="password" required
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Konfirmasi Password <span class="text-red-400">*</span></label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 rounded border-gray-600 bg-gray-700">
                    <span class="text-sm text-gray-300">Akun Aktif</span>
                </label>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.users.index') }}"
                   class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">Batal</a>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection
