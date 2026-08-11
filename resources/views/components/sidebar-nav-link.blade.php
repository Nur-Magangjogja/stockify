@props([
    'href',
    'active' => false,
])

<a href="{{ $href }}"
   {{ $attributes->class([
       'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all',
       'sidebar-item-active'                             => $active,
       'text-gray-300 hover:bg-gray-700 hover:text-white' => ! $active,
   ]) }}
   @if ($active) aria-current="page" @endif>

    {{-- Icon slot (SVG) --}}
    @isset($icon)
        <span class="w-5 h-5 flex-shrink-0" aria-hidden="true">
            {{ $icon }}
        </span>
    @endisset

    {{-- Label --}}
    {{ $slot }}
</a>
