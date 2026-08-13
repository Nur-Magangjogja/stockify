@props(['action' => request()->url(), 'placeholder' => 'Cari...', 'search' => request('search'), 'hideDefault' => false])

<form method="GET" action="{{ $action }}" class="flex flex-col md:flex-row flex-wrap gap-3 w-full sm:w-auto">
    {{-- Preserve existing query strings except search and page --}}
    @foreach(request()->query() as $key => $value)
        @if($key !== 'search' && $key !== 'page' && !is_array($value))
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    @unless($hideDefault)
    <div class="relative w-full sm:w-auto flex-1 min-w-[200px]">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" name="search" value="{{ $search }}"
            class="input-field block w-full pl-10 pr-3 py-2 text-sm rounded-lg"
            placeholder="{{ $placeholder }}">
    </div>
    @endunless

    {{ $slot }}

    <button type="submit" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-900 transition-colors shadow-lg shadow-blue-900/20">
        Filter
    </button>
    
    @if(request()->query())
        <a href="{{ $action }}" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-300 bg-gray-700 rounded-lg hover:bg-gray-600 text-center transition-colors">
            Reset
        </a>
    @endif
</form>
