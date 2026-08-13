@props([
    'value',
    'label',
    'color' => 'blue',
    'sub' => null,
])

@php
    $colorMap = [
        'blue' => [
            'bg' => 'bg-blue-900/20',
            'border' => 'border-blue-700/30',
            'icon_bg' => 'bg-blue-500/20',
            'icon_text' => 'text-blue-400',
            'value_text' => 'text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200',
            'glow' => 'hover:shadow-blue-900/30',
        ],
        'green' => [
            'bg' => 'bg-green-900/20',
            'border' => 'border-green-700/30',
            'icon_bg' => 'bg-green-500/20',
            'icon_text' => 'text-green-400',
            'value_text' => 'text-transparent bg-clip-text bg-gradient-to-r from-white to-green-200',
            'glow' => 'hover:shadow-green-900/30',
        ],
        'red' => [
            'bg' => 'bg-red-900/20',
            'border' => 'border-red-700/30',
            'icon_bg' => 'bg-red-500/20',
            'icon_text' => 'text-red-400',
            'value_text' => 'text-transparent bg-clip-text bg-gradient-to-r from-white to-red-200',
            'glow' => 'hover:shadow-red-900/30',
        ],
        'amber' => [
            'bg' => 'bg-amber-900/20',
            'border' => 'border-amber-700/30',
            'icon_bg' => 'bg-amber-500/20',
            'icon_text' => 'text-amber-400',
            'value_text' => 'text-transparent bg-clip-text bg-gradient-to-r from-white to-amber-200',
            'glow' => 'hover:shadow-amber-900/30',
        ],
        'purple' => [
            'bg' => 'bg-purple-900/20',
            'border' => 'border-purple-700/30',
            'icon_bg' => 'bg-purple-500/20',
            'icon_text' => 'text-purple-400',
            'value_text' => 'text-transparent bg-clip-text bg-gradient-to-r from-white to-purple-200',
            'glow' => 'hover:shadow-purple-900/30',
        ],
        'indigo' => [
            'bg' => 'bg-indigo-900/20',
            'border' => 'border-indigo-700/30',
            'icon_bg' => 'bg-indigo-500/20',
            'icon_text' => 'text-indigo-400',
            'value_text' => 'text-transparent bg-clip-text bg-gradient-to-r from-white to-indigo-200',
            'glow' => 'hover:shadow-indigo-900/30',
        ],
    ];
    $theme = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="relative overflow-hidden rounded-2xl border {{ $theme['border'] }} {{ $theme['bg'] }} backdrop-blur-md p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl {{ $theme['glow'] }} group">
    {{-- Subtle decorative glow inside card --}}
    <div class="absolute -top-10 -right-10 w-24 h-24 {{ $theme['icon_bg'] }} rounded-full blur-2xl opacity-50 transition-opacity group-hover:opacity-100"></div>

    <div class="relative z-10 flex items-start justify-between">
        <div>
            <p class="text-xs font-medium text-gray-400 tracking-wider uppercase mb-1">{{ $label }}</p>
            <h3 class="text-3xl font-bold {{ $theme['value_text'] }}">{{ $value }}</h3>
            @if($sub)
            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full {{ $theme['icon_bg'] }} border border-{{ $color }}-500/50"></span>
                {{ $sub }}
            </p>
            @endif
        </div>
        @isset($icon)
        <div class="w-12 h-12 rounded-xl {{ $theme['icon_bg'] }} flex items-center justify-center p-3 shadow-inner {{ $theme['icon_text'] }} transition-transform duration-300 group-hover:scale-110">
            {{ $icon }}
        </div>
        @endisset
    </div>
</div>
