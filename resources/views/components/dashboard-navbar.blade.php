@php
    $role = auth()->user()->role;
    $prefix = match ($role) {
        'Admin' => 'admin',
        'Manajer Gudang' => 'manager',
        default => 'staff',
    };
    $lowStockCount = \App\Models\Products::lowStock()->count();
@endphp

<nav class="sticky top-0 z-30 flex items-center gap-4 border-b border-white/5 bg-gray-900/70 backdrop-blur-md px-4 py-3 shadow-sm transition-all duration-300">
    <button type="button" data-drawer-target="sidebar" data-drawer-toggle="sidebar"
        class="rounded-lg p-2 text-gray-400 hover:bg-gray-700 hover:text-white sm:hidden" aria-label="Buka menu">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
        </svg>
    </button>

    <div class="min-w-0 flex-1">
        <h2 class="truncate text-lg font-semibold text-white">@yield('page-title', 'Dashboard')</h2>
        <p class="truncate text-xs text-gray-400">@yield('page-subtitle', '')</p>
    </div>

    <div class="flex items-center gap-3">
        @if($lowStockCount > 0 && $role !== 'Staff Gudang')
        <a href="{{ route("{$prefix}.products.index", ['stock_status' => 'low']) }}"
            class="relative rounded-lg p-2 text-amber-400 transition hover:bg-amber-900/20" title="Stok menipis">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-white">{{ $lowStockCount > 9 ? '9+' : $lowStockCount }}</span>
        </a>
        @endif
        <div class="hidden text-right sm:block">
            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400">{{ now()->translatedFormat('d M Y') }}</p>
        </div>
    </div>
</nav>
