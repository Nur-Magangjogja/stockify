@props([
    'label',
    'active' => false,
    'groupId',
])

<li>
    <button type="button"
        class="flex items-center w-full gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
               {{ $active ? 'text-blue-400' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
        data-collapse-toggle="{{ $groupId }}"
        aria-controls="{{ $groupId }}"
        aria-expanded="{{ $active ? 'true' : 'false' }}">

        {{-- Icon slot --}}
        @isset($icon)
            <span class="w-5 h-5 flex-shrink-0" aria-hidden="true">
                {{ $icon }}
            </span>
        @endisset

        {{ $label }}

        {{-- Chevron --}}
        <svg class="w-4 h-4 ml-auto transition-transform {{ $active ? 'rotate-180' : '' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Sub-items --}}
    <ul id="{{ $groupId }}"
        class="{{ $active ? '' : 'hidden' }} py-1 pl-8 space-y-1">
        {{ $slot }}
    </ul>
</li>
