@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Pengguna')
@section('page-subtitle', 'Kelola akun pengguna sistem')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
            placeholder="Cari nama atau email..."
            class="bg-gray-800 border border-gray-700 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 w-52">
        <select name="role" class="bg-gray-800 border border-gray-700 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Semua Role</option>
            <option value="Admin" {{ ($filters['role'] ?? '') === 'Admin' ? 'selected' : '' }}>Admin</option>
            <option value="Manajer Gudang" {{ ($filters['role'] ?? '') === 'Manajer Gudang' ? 'selected' : '' }}>Manajer Gudang</option>
            <option value="Staff Gudang" {{ ($filters['role'] ?? '') === 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
        </select>
        <button type="submit" class="px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-lg transition-all">Filter</button>
    </form>
    <a href="{{ route('admin.users.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah User
    </a>
</div>

<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="border-b border-gray-700">
            <tr>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Pengguna</th>
                <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Role</th>
                <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($users as $user)
            <tr class="hover:bg-gray-750 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                        <div>
                            <p class="font-medium text-white">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4">
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full
                        {{ $user->role === 'Admin' ? 'bg-purple-900/40 text-purple-400' :
                           ($user->role === 'Manajer Gudang' ? 'bg-blue-900/40 text-blue-400' : 'bg-gray-700 text-gray-300') }}">
                        {{ $user->role_label }}
                    </span>
                </td>
                <td class="px-4 py-4">
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-900/40 text-green-400' : 'bg-red-900/40 text-red-400' }}">
                        {{ $user->is_active ? 'Aktif' : 'Non-aktif' }}
                    </span>
                </td>
                <td class="px-4 py-4 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="p-1.5 text-gray-400 hover:text-amber-400 rounded-lg hover:bg-amber-900/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="p-1.5 text-gray-400 hover:text-{{ $user->is_active ? 'amber' : 'green' }}-400 rounded-lg transition-all"
                                    title="{{ $user->is_active ? 'Non-aktifkan' : 'Aktifkan' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $user->is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                                </svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Hapus user {{ addslashes($user->name) }}?')">
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
            <x-table-empty :colspan="5" message="Tidak ada pengguna ditemukan" />
            @endforelse
        </tbody>
    </table>
    <x-table-pagination :paginator="$users" />
</div>
@endsection
