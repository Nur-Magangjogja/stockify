@extends('layouts.app')
@section('title', 'Manajemen Kategori')
@section('page-title', 'Kategori Produk')
@section('page-subtitle', 'Kelola kategori produk')

@section('content')
<div class="flex justify-between items-center mb-5">
    <span class="text-sm text-gray-400">{{ $categories->total() }} kategori</span>
    <a href="{{ route('admin.categories.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </a>
</div>

<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="border-b border-gray-700">
            <tr>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Nama Kategori</th>
                <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-750 transition-colors">
                <td class="px-5 py-4">
                    <div>
                        <p class="font-medium text-white">{{ $category->name }}</p>
                        @if($category->description)
                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $category->description }}</p>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-4 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                           class="p-1.5 text-gray-400 hover:text-amber-400 rounded-lg hover:bg-amber-900/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @if($category->products_count == 0)
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                              onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-gray-400 hover:text-red-400 rounded-lg hover:bg-red-900/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-500 text-sm">Belum ada kategori</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($categories->hasPages())
    <div class="px-5 py-4 border-t border-gray-700">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
