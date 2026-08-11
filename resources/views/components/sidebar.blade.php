{{-- Sidebar Utama --}}
<aside id="sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800 border-r border-gray-700 flex flex-col">

        {{-- Logo & Brand --}}
        <x-sidebar-logo />

        {{-- Navigation Links --}}
        @php
            $role    = auth()->user()->role;
            $prefix  = match($role) {
                'Admin'          => 'admin',
                'Manajer Gudang' => 'manager',
                default          => 'staff',
            };
            $stockActive = request()->routeIs("{$prefix}.stocks*");
        @endphp

        <ul class="space-y-1 flex-1">

            {{-- Dashboard --}}
            <li>
                <x-sidebar-nav-link
                    href="{{ route($prefix . '.dashboard') }}"
                    :active="request()->routeIs($prefix . '.dashboard')">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </x-slot:icon>
                    Dashboard
                </x-sidebar-nav-link>
            </li>

            @if($role === 'Admin' || $role === 'Manajer Gudang')
            {{-- Produk --}}
            <li>
                <x-sidebar-nav-link
                    href="{{ route($prefix . '.products.index') }}"
                    :active="request()->routeIs($prefix . '.products*')">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </x-slot:icon>
                    Produk
                </x-sidebar-nav-link>
            </li>
            @endif

            @if($role === 'Admin')
            {{-- Kategori --}}
            <li>
                <x-sidebar-nav-link
                    href="{{ route('admin.categories.index') }}"
                    :active="request()->routeIs('admin.categories*')">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </x-slot:icon>
                    Kategori
                </x-sidebar-nav-link>
            </li>
            @endif

            @if($role === 'Admin' || $role === 'Manajer Gudang')
            {{-- Stok (dropdown group) --}}
            <x-sidebar-nav-group
                label="Stok"
                group-id="stock-menu"
                :active="$stockActive">
                <x-slot:icon>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </x-slot:icon>

                <x-sidebar-nav-sublink
                    href="{{ route($prefix . '.stocks.index') }}"
                    :active="request()->routeIs($prefix . '.stocks.index')">
                    Riwayat Transaksi
                </x-sidebar-nav-sublink>

                <x-sidebar-nav-sublink
                    href="{{ route($prefix . '.stocks.in.create') }}"
                    :active="request()->routeIs($prefix . '.stocks.in.create')">
                    Barang Masuk
                </x-sidebar-nav-sublink>

                <x-sidebar-nav-sublink
                    href="{{ route($prefix . '.stocks.out.create') }}"
                    :active="request()->routeIs($prefix . '.stocks.out.create')">
                    Barang Keluar
                </x-sidebar-nav-sublink>

                <x-sidebar-nav-sublink
                    href="{{ route($prefix . '.stocks.opname.index') }}"
                    :active="request()->routeIs($prefix . '.stocks.opname*')">
                    Stock Opname
                </x-sidebar-nav-sublink>
            </x-sidebar-nav-group>
            @endif

            @if($role === 'Admin' || $role === 'Manajer Gudang')
            {{-- Supplier --}}
            <li>
                <x-sidebar-nav-link
                    href="{{ route($prefix . '.suppliers.index') }}"
                    :active="request()->routeIs($prefix . '.suppliers*')">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </x-slot:icon>
                    Supplier
                </x-sidebar-nav-link>
            </li>
            @endif

            @if($role === 'Admin')
            {{-- Pengguna --}}
            <li>
                <x-sidebar-nav-link
                    href="{{ route('admin.users.index') }}"
                    :active="request()->routeIs('admin.users*')">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </x-slot:icon>
                    Pengguna
                </x-sidebar-nav-link>
            </li>
            @endif

            @if($role !== 'Staff Gudang')
            {{-- Laporan --}}
            <li>
                <x-sidebar-nav-link
                    href="{{ route($prefix . '.reports.index') }}"
                    :active="request()->routeIs($prefix . '.reports*')">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </x-slot:icon>
                    Laporan
                </x-sidebar-nav-link>
            </li>
            @endif

        </ul>

        {{-- User Footer --}}
        <x-sidebar-user />

    </div>
</aside>
