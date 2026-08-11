@props([
    'stock',
    'minStock' => null,
    'unit'     => '',
    'showMin'  => false,
])

@php
$isOut  = $stock <= 0;
$isLow  = !$isOut && $minStock !== null && $stock <= $minStock;

$badgeClass = $isOut
    ? 'bg-red-900/40 text-red-400'
    : ($isLow ? 'bg-amber-900/40 text-amber-400' : 'bg-green-900/40 text-green-400');

$label = $isOut ? 'Habis' : ($isLow ? 'Menipis' : 'Tersedia');
@endphp

<span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">{{ $label }}</span>

@if($showMin && $isLow && $minStock !== null)
<span class="ml-1.5 text-xs bg-amber-900/50 text-amber-400 px-1.5 py-0.5 rounded-full">min:{{ $minStock }}</span>
@endif
