@props([
    'title',
    'href'      => null,    {{-- URL tombol tambah --}}
    'btnLabel'  => 'Tambah',
    'count'     => null,    {{-- angka total record, opsional --}}
    'countLabel'=> '',      {{-- label setelah angka, e.g. "produk ditemukan" --}}
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div class="flex items-center gap-2">
        @if($count !== null)
        <span class="text-sm text-gray-400">{{ $count }} {{ $countLabel }}</span>
        @endif
    </div>

    @if($href)
    <a href="{{ $href }}"
       class="flex items-center gap-2 px-4 py-2.5 btn-primary text-white text-sm font-medium rounded-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        {{ $btnLabel }}
    </a>
    @endif
</div>
