<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $appName) — {{ $appName }}</title>
    <meta name="description" content="@yield('meta_description', $appName . ' - Sistem Manajemen Stok Gudang')">

    {{-- Flowbite & Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-900 via-[#111827] to-[#0f172a] text-gray-100 antialiased min-h-screen">

{{-- Sidebar --}}
<x-sidebar />

{{-- Main content --}}
<div class="sm:ml-64">
    <x-dashboard-navbar />

    {{-- Page Content --}}
    <main class="p-4 min-h-screen">
        {{-- Flash Messages --}}
        <x-alert-flash />

        {{-- Validation Errors --}}
        <x-form-errors />

        @yield('content')
    </main>

    <x-dashboard-footer />
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
