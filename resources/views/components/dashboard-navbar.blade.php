@php
    $role = auth()->user()->role;
    $prefix = match ($role) {
        'Admin' => 'admin',
        'Manajer Gudang' => 'manager',
        default => 'staff',
    };
    // Menggunakan cache selama 5 menit agar tidak membebani database setiap kali pindah halaman
    $lowStockProducts = \Illuminate\Support\Facades\Cache::remember('low_stock_products', 300, function() {
        return \App\Models\Products::lowStock()->take(5)->get();
    });
    $lowStockCount = \Illuminate\Support\Facades\Cache::remember('low_stock_count', 300, function() {
        return \App\Models\Products::lowStock()->count();
    });
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
        @if($role !== 'Staff Gudang')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" 
                class="relative rounded-xl p-2 text-gray-400 transition hover:bg-gray-800 hover:text-white focus:outline-none" title="Notifikasi Stok">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($lowStockCount > 0)
                <span class="absolute right-1.5 top-1.5 flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-gray-900"></span>
                </span>
                @endif
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="absolute right-0 mt-3 w-80 rounded-xl bg-gray-800 shadow-2xl border border-gray-700 overflow-hidden z-50"
                 style="display: none;">
                
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-700 bg-gray-800/50">
                    <h3 class="text-sm font-semibold text-white">Notifikasi Stok</h3>
                    @if($lowStockCount > 0)
                        <span class="bg-red-500/20 text-red-400 text-xs px-2 py-0.5 rounded-full font-medium">{{ $lowStockCount }} Peringatan</span>
                    @endif
                </div>

                <div class="max-h-[320px] overflow-y-auto custom-scrollbar">
                    @if($lowStockCount > 0)
                        <div class="flex flex-col divide-y divide-gray-700/50">
                            @foreach($lowStockProducts as $product)
                            <a href="{{ route("{$prefix}.products.show", $product->id) }}" class="flex items-start gap-3 p-4 hover:bg-gray-750 transition-colors">
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($product->stock <= 0)
                                        <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-amber-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Sisa stok: <span class="{{ $product->stock <= 0 ? 'text-red-400 font-bold' : 'text-amber-400 font-bold' }}">{{ $product->stock }}</span> (Min: {{ $product->minimum_stock }})</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm font-medium text-white">Semua Stok Aman</p>
                            <p class="text-xs text-gray-400 mt-1">Tidak ada produk yang perlu di-restock saat ini.</p>
                        </div>
                    @endif
                </div>
                
                @if($lowStockCount > 5)
                <div class="p-3 border-t border-gray-700 bg-gray-800/80">
                    <a href="{{ route("{$prefix}.products.index", ['stock_status' => 'low']) }}" class="block w-full text-center py-2 text-xs font-medium text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-colors">
                        Lihat Semua ({{ $lowStockCount }})
                    </a>
                </div>
                @elseif($lowStockCount > 0 && $lowStockCount <= 5)
                <div class="p-3 border-t border-gray-700 bg-gray-800/80">
                    <a href="{{ route("{$prefix}.products.index", ['stock_status' => 'low']) }}" class="block w-full text-center py-2 text-xs font-medium text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-colors">
                        Buka Halaman Produk
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
        <div class="hidden text-right sm:block">
            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400">{{ now()->translatedFormat('d M Y') }}</p>
        </div>
    </div>
</nav>
