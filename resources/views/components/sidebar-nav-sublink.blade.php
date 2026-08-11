@props([
    'href',
    'active' => false,
])

<li>
    <a href="{{ $href }}"
       {{ $attributes->class([
           'block px-3 py-2 text-sm rounded-lg transition-all',
           'text-blue-400 font-medium'                        => $active,
           'text-gray-400 hover:bg-gray-700 hover:text-white' => ! $active,
       ]) }}
       @if ($active) aria-current="page" @endif>
        {{ $slot }}
    </a>
</li>
