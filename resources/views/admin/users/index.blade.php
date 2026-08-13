@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Pengguna')
@section('page-subtitle', 'Kelola akun pengguna sistem')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <x-search-filter :action="request()->url()" placeholder="Cari nama atau email..." :search="$filters['search'] ?? ''">
        <select name="role" class="input-field w-full sm:w-auto text-sm rounded-lg px-3 py-2">
            <option value="">Semua Role</option>
            <option value="Admin" {{ ($filters['role'] ?? '') === 'Admin' ? 'selected' : '' }}>Admin</option>
            <option value="Manajer Gudang" {{ ($filters['role'] ?? '') === 'Manajer Gudang' ? 'selected' : '' }}>Manajer Gudang</option>
            <option value="Staff Gudang" {{ ($filters['role'] ?? '') === 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
        </select>
    </x-search-filter>
    <a href="{{ route('admin.users.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah User
    </a>
</div>

<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <table class="table-standard">
        <thead class="table-head">
            <tr>
                <th class="table-cell-head">Pengguna</th>
                <th class="table-cell-head">Role</th>
                <th class="table-cell-head">Status</th>
                <th class="table-cell-head text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($users as $user)
            <tr class="table-row">
                <td class="table-cell">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                        <div>
                            <p class="font-medium text-white">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="table-cell">
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full
                        {{ $user->role === 'Admin' ? 'bg-purple-900/40 text-purple-400' :
                           ($user->role === 'Manajer Gudang' ? 'bg-blue-900/40 text-blue-400' : 'bg-gray-700 text-gray-300') }}">
                        {{ $user->role_label }}
                    </span>
                </td>
                <td class="table-cell">
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-900/40 text-green-400' : 'bg-red-900/40 text-red-400' }}">
                        {{ $user->is_active ? 'Aktif' : 'Non-aktif' }}
                    </span>
                </td>
                <td class="table-cell text-right">
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
                        <x-delete-button 
                            action="{{ route('admin.users.destroy', $user->id) }}" 
                            message="Hapus user {{ addslashes($user->name) }}?" 
                            class="p-1.5 text-gray-400 hover:text-red-400 rounded-lg hover:bg-red-900/20 transition-all border-none bg-transparent" />
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
