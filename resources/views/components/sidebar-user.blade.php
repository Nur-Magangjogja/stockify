{{-- Sidebar User Footer — info user yang login + tombol logout --}}
<div class="pt-4 mt-4 border-t border-gray-700">
    <div class="flex items-center gap-3 px-3 py-2">
        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar"
             class="w-8 h-8 rounded-full object-cover flex-shrink-0">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->role_label }}</p>
        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="mt-1">
        @csrf
        <button type="submit"
            class="flex items-center gap-3 w-full px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Keluar
        </button>
    </form>
</div>
