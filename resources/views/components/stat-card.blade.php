@props([
    'value',
    'label',
    'sub'    => '',
    'color'  => 'blue',   {{-- blue | green | amber | red | purple --}}
])

@php
$gradients = [
    'blue'   => 'gradient-card',
    'green'  => 'gradient-card-green',
    'amber'  => 'gradient-card-amber',
    'red'    => 'gradient-card-red',
    'purple' => 'bg-purple-900/40 border border-purple-700',
];
$subColors = [
    'blue'   => 'text-blue-200',
    'green'  => 'text-green-200',
    'amber'  => 'text-amber-200',
    'red'    => 'text-red-200',
    'purple' => 'text-purple-200',
];
$gradient = $gradients[$color] ?? $gradients['blue'];
$subColor = $subColors[$color]  ?? $subColors['blue'];
@endphp

<div class="{{ $gradient }} rounded-xl p-5 text-white shadow-lg">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="{{ $subColor }} text-xs font-medium uppercase tracking-wide">{{ $label }}</p>
            <p class="text-3xl font-bold mt-1">{{ $value }}</p>
            @if($sub)
            <p class="{{ $subColor }} text-xs mt-2">{{ $sub }}</p>
            @endif
        </div>
        @isset($icon)
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0 ml-3">
            <span class="w-6 h-6 text-white" aria-hidden="true">{{ $icon }}</span>
        </div>
        @endisset
    </div>
</div>
