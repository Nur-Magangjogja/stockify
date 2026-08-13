@props([
    'action', 
    'message' => 'Apakah Anda yakin ingin menghapus data ini?', 
    'class' => 'px-3 py-1.5 bg-red-600/20 text-red-500 hover:bg-red-500 hover:text-white rounded transition-colors text-xs font-medium border border-red-500/50 hover:border-red-500 flex items-center gap-1.5',
    'method' => 'DELETE',
    'hideIcon' => false
])

<form action="{{ $action }}" method="POST" class="inline" onsubmit="return confirm('{{ $message }}')">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <button type="submit" class="{{ $class }}" title="{{ $attributes->get('title', 'Hapus') }}">
        @unless($hideIcon)
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        @endunless
        {{ $slot }}
    </button>
</form>
