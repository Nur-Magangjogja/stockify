{{-- Sidebar Logo --}}
<div class="flex items-center gap-3 px-3 py-4 mb-4 border-b border-gray-700">
    @if(isset($appLogo) && $appLogo)
        <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center flex-shrink-0 p-1 border border-gray-700">
            <img src="{{ asset($appLogo) }}" alt="Logo" class="w-full h-full object-contain">
        </div>
    @else
        <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
    @endif
    <div>
        <span class="text-lg font-bold text-white leading-none">{{ $appName ?? 'Stockify' }}</span>
        <p class="text-xs text-gray-400 mt-0.5">
            @if(auth()->user()->isAdmin()) Admin
            @elseif(auth()->user()->isManager()) Manajer Gudang
            @else Staff Gudang
            @endif
        </p>
    </div>
</div>
