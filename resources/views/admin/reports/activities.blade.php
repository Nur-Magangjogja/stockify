@extends('layouts.app')
@section('title', 'Laporan Aktivitas')
@section('page-title', 'Laporan Aktivitas Pengguna')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-5">
    <x-search-filter :action="request()->url()" :hideDefault="true">
        <div>
            <label class="block text-xs text-gray-400 mb-1">Pengguna</label>
            <select name="user_id" class="input-field text-sm rounded-lg px-3 py-2">
                <option value="">Semua</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ ($filters['user_id'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Dari</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                class="input-field text-sm rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Sampai</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                class="input-field text-sm rounded-lg px-3 py-2">
        </div>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" target="_blank"
           class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-all w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Excel
        </a>
    </x-search-filter>
</div>

<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-standard">
            <thead class="table-head">
                <tr>
                    <th class="table-cell-head">Pengguna</th>
                    <th class="table-cell-head">Aksi</th>
                    <th class="table-cell-head">Deskripsi</th>
                    <th class="table-cell-head">Peran</th>
                    <th class="table-cell-head">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($activities as $log)
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="flex items-center gap-2">
                            <img src="{{ $log->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=System&background=374151&color=9ca3af' }}"
                                 class="w-7 h-7 rounded-full flex-shrink-0" alt="">
                            <span class="text-white text-sm">{{ $log->user?->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="table-cell">
                        <span class="font-mono text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded">{{ $log->action }}</span>
                    </td>
                    <td class="table-cell text-gray-400 text-sm max-w-md whitespace-pre-wrap">{{ $log->description }}</td>
                    <td class="table-cell text-xs text-gray-300">{{ $log->user?->role ?? 'Sistem' }}</td>
                    <td class="table-cell text-gray-400 text-xs">{{ $log->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
            <x-table-empty :colspan="5" message="Tidak ada data aktivitas" />
            @endforelse
        </tbody>
    </table>
    </div>
    <x-table-pagination :paginator="$activities" />
</div>
@endsection
