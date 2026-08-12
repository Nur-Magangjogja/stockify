@props([
    'name' => auth()->user()->name ?? 'Pengguna',
    'subtitle' => ''
])

@php
    // Menggunakan timezone Asia/Jakarta agar sinkron dengan waktu lokal
    $hour = (int) \Carbon\Carbon::now('Asia/Jakarta')->format('H');
    
    if ($hour < 10) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour < 14) {
        $greeting = 'Selamat Siang';
    } elseif ($hour < 18) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }
@endphp

<div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-800 to-purple-900 rounded-2xl p-6 md:p-8 mb-6 shadow-xl shadow-blue-900/20 border border-white/10">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-5 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-32 -mb-20 w-48 h-48 rounded-full bg-blue-400 opacity-10 blur-2xl pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-blue-200 text-sm font-medium mb-1.5 tracking-wide">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-1.5 tracking-tight">
                {{ $greeting }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-indigo-100">{{ $name }}</span> 👋
            </h1>
            <p class="text-indigo-100/80 text-sm md:text-base font-light">
                {{ $subtitle }}
            </p>
        </div>
        
        {{-- Optional Right Side Content (e.g. Action Button) --}}
        @isset($action)
        <div class="flex-shrink-0 mt-4 md:mt-0">
            {{ $action }}
        </div>
        @endisset
    </div>
</div>
