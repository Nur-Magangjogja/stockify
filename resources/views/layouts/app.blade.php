<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stockify') — Stockify</title>
    <meta name="description" content="@yield('meta_description', 'Stockify - Sistem Manajemen Stok Gudang')">

    {{-- Flowbite & Tailwind --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --color-primary: #2563EB;
            --color-primary-dark: #1D4ED8;
        }
        .sidebar-item-active {
            background: rgba(37, 99, 235, 0.15);
            color: #60A5FA;
            border-right: 3px solid #2563EB;
        }
        .sidebar-item-active svg { color: #60A5FA !important; }
        .stock-badge-in { background: rgba(34, 197, 94, 0.15); color: #16a34a; }
        .stock-badge-out { background: rgba(239, 68, 68, 0.15); color: #dc2626; }
        .gradient-card { background: linear-gradient(135deg, #1e3a5f 0%, #2563EB 100%); }
        .gradient-card-green { background: linear-gradient(135deg, #064e3b 0%, #059669 100%); }
        .gradient-card-amber { background: linear-gradient(135deg, #78350f 0%, #d97706 100%); }
        .gradient-card-red { background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 100%); }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-900 text-gray-100 antialiased">

{{-- Sidebar --}}
<aside id="sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800 border-r border-gray-700 flex flex-col">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-3 py-4 mb-4 border-b border-gray-700">
            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white leading-none">Stockify</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    @if(auth()->user()->isAdmin()) Admin
                    @elseif(auth()->user()->isManager()) Manajer Gudang
                    @else Staff Gudang
                    @endif
                </p>
            </div>
        </div>

        {{-- Navigation --}}
        <ul class="space-y-1 flex-1">
            @php
                $role    = auth()->user()->role;
                $prefix  = match($role) {
                    'Admin'          => 'admin',
                    'Manajer Gudang' => 'manager',
                    default             => 'staff',
                };
            @endphp

            {{-- Dashboard --}}
            <li>
                <a href="{{ route("{$prefix}.dashboard") }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all
                          {{ request()->routeIs("{$prefix}.dashboard") ? 'sidebar-item-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            </li>

            @if($role === 'Admin' || $role === 'Manajer Gudang')
            {{-- Products --}}
            <li>
                <a href="{{ route("{$prefix}.products.index") }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all
                          {{ request()->routeIs("{$prefix}.products*") ? 'sidebar-item-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Produk
                </a>
            </li>
            @endif

            @if($role === 'Admin')
            {{-- Categories --}}
            <li>
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all
                          {{ request()->routeIs('admin.categories*') ? 'sidebar-item-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kategori
                </a>
            </li>
            @endif

            @if($role === 'Admin' || $role === 'Manajer Gudang')
            {{-- Stok --}}
            <li>
                <button type="button"
                    class="flex items-center w-full gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all"
                    data-collapse-toggle="stock-menu" aria-controls="stock-menu">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Stok
                    <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul id="stock-menu" class="hidden py-1 pl-8 space-y-1">
                    <li>
                        <a href="{{ route("{$prefix}.stocks.index") }}"
                           class="block px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-gray-700 hover:text-white transition-all
                                  {{ request()->routeIs("{$prefix}.stocks.index") ? 'text-blue-400' : '' }}">
                            Riwayat Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("{$prefix}.stocks.in.create") }}"
                           class="block px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-gray-700 hover:text-white transition-all">
                            Barang Masuk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("{$prefix}.stocks.out.create") }}"
                           class="block px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-gray-700 hover:text-white transition-all">
                            Barang Keluar
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("{$prefix}.stocks.opname.index") }}"
                           class="block px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-gray-700 hover:text-white transition-all">
                            Stock Opname
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- Suppliers --}}
            @if($role === 'Admin' || $role === 'Manajer Gudang')
            <li>
                <a href="{{ route("{$prefix}.suppliers.index") }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all
                          {{ request()->routeIs("{$prefix}.suppliers*") ? 'sidebar-item-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Supplier
                </a>
            </li>
            @endif

            @if($role === 'Admin')
            {{-- Users --}}
            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all
                          {{ request()->routeIs('admin.users*') ? 'sidebar-item-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Pengguna
                </a>
            </li>
            @endif

            {{-- Reports --}}
            @if($role !== 'Staff Gudang')
            <li>
                <a href="{{ route("{$prefix}.reports.index") }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all
                          {{ request()->routeIs("{$prefix}.reports*") ? 'sidebar-item-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan
                </a>
            </li>
            @endif
        </ul>

        {{-- User Footer --}}
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
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Main content --}}
<div class="sm:ml-64">
    {{-- Top Bar --}}
    <nav class="bg-gray-800 border-b border-gray-700 px-4 py-3 flex items-center gap-4">
        <button type="button" data-drawer-target="sidebar" data-drawer-toggle="sidebar"
            class="sm:hidden p-2 text-gray-400 rounded-lg hover:bg-gray-700">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
            </svg>
        </button>

        <div class="flex-1">
            <h2 class="text-lg font-semibold text-white">@yield('page-title', 'Dashboard')</h2>
            <p class="text-xs text-gray-400">@yield('page-subtitle', '')</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Notification badge for low stock --}}
            @php $lowStockCount = \App\Models\Products::lowStock()->count(); @endphp
            @if($lowStockCount > 0)
            <div class="relative">
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.products.index' : 'manager.products.index', ['stock_status' => 'low']) }}"
                   class="p-2 text-amber-400 rounded-lg hover:bg-amber-900/20 transition-all relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 text-xs text-white rounded-full flex items-center justify-center font-bold">
                        {{ $lowStockCount > 9 ? '9+' : $lowStockCount }}
                    </span>
                </a>
            </div>
            @endif

            <div class="text-right hidden sm:block">
                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">{{ now()->translatedFormat('d M Y') }}</p>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class="p-4 min-h-screen">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div id="alert-success" class="flex items-start gap-3 p-4 mb-4 rounded-lg bg-green-900/30 border border-green-700 text-green-300" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
            </svg>
            <span class="text-sm">{!! session('success') !!}</span>
            <button type="button" class="ml-auto text-green-400 hover:text-white" data-dismiss-target="#alert-success">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                </svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div id="alert-error" class="flex items-start gap-3 p-4 mb-4 rounded-lg bg-red-900/30 border border-red-700 text-red-300" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
            </svg>
            <span class="text-sm">{!! session('error') !!}</span>
            <button type="button" class="ml-auto text-red-400 hover:text-white" data-dismiss-target="#alert-error">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                </svg>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 mb-4 rounded-lg bg-red-900/30 border border-red-700 text-red-300">
            <p class="text-sm font-semibold mb-1">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
