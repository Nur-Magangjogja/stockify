@props([
    'colspan' => 1,
    'message' => 'Tidak ada data ditemukan',
])

<tr>
    <td colspan="{{ $colspan }}" class="px-5 py-16 text-center">
        <div class="text-gray-500">
            {{ $icon ?? '' }}
            <p class="text-sm font-medium">{{ $message }}</p>
            @isset($sub)
            <p class="text-xs mt-1 text-gray-600">{{ $sub }}</p>
            @endisset
        </div>
    </td>
</tr>
