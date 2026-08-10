@extends('layouts.app')
@section('title', 'Detail Stock Opname')
@section('page-title', 'Stock Opname #' . $opname->reference_number)
@section('page-subtitle', $opname->status_label . ' — ' . $opname->opname_date->format('d M Y'))

@section('content')
@php $prefix = auth()->user()->isAdmin() ? 'admin' : 'manager'; @endphp

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route("{$prefix}.stocks.opname.index") }}"
       class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    @if($opname->status !== 'completed' && $opname->status !== 'cancelled')
    <form action="{{ route("{$prefix}.stocks.opname.complete", $opname->id) }}" method="POST" class="ml-auto"
          onsubmit="return confirm('Selesaikan opname ini? Stok produk akan diperbarui sesuai data fisik.')">
        @csrf
        <button class="px-4 py-2 bg-green-700 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-all">
            ✓ Selesaikan Opname
        </button>
    </form>
    @endif
</div>

@if($opname->status !== 'completed')
<form action="{{ route("{$prefix}.stocks.opname.update", $opname->id) }}" method="POST" id="opname-form">
    @csrf @method('PUT')
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
@endif

<div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden mb-4">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="border-b border-gray-700">
                <tr>
                    <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase">Produk</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase text-center">Stok Sistem</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase text-center">Stok Fisik</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase text-center">Selisih</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($opname->details as $i => $detail)
                <tr class="hover:bg-gray-750 transition-colors">
                    <td class="px-5 py-3.5">
                        <input type="hidden" name="details[{{ $i }}][id]" value="{{ $detail->id }}">
                        <input type="hidden" name="details[{{ $i }}][system_stock]" value="{{ $detail->system_stock }}">
                        <p class="text-white font-medium">{{ $detail->product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $detail->product->code }} · {{ $detail->product->unit }}</p>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-mono text-gray-300">{{ $detail->system_stock }}</span>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        @if($opname->status !== 'completed')
                        <input type="number" name="details[{{ $i }}][actual_stock]" min="0"
                            value="{{ $detail->actual_stock }}"
                            class="w-20 bg-gray-900 border border-gray-600 text-center text-white text-sm rounded-lg px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500"
                            data-system="{{ $detail->system_stock }}"
                            oninput="updateDiff(this)">
                        @else
                        <span class="font-mono text-white">{{ $detail->actual_stock }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center" id="diff-{{ $i }}">
                        @php $diff = $detail->difference; @endphp
                        <span class="font-semibold {{ $diff > 0 ? 'text-green-400' : ($diff < 0 ? 'text-red-400' : 'text-gray-400') }}">
                            {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        @if($opname->status !== 'completed')
                        <input type="text" name="details[{{ $i }}][notes]"
                            value="{{ $detail->notes }}"
                            class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Catatan">
                        @else
                        <span class="text-gray-400 text-xs">{{ $detail->notes ?? '-' }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($opname->status !== 'completed')
<div class="flex justify-end">
    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">
        Simpan Perubahan
    </button>
</div>
</form>
@endif

@endsection

@push('scripts')
<script>
function updateDiff(input) {
    const row = input.closest('tr');
    const systemStock = parseInt(input.dataset.system);
    const actualStock = parseInt(input.value) || 0;
    const diff = actualStock - systemStock;
    const diffIdx = input.name.match(/\[(\d+)\]/)[1];
    const diffEl = document.getElementById('diff-' + diffIdx);
    diffEl.innerHTML = `<span class="font-semibold ${diff > 0 ? 'text-green-400' : diff < 0 ? 'text-red-400' : 'text-gray-400'}">${diff > 0 ? '+' : ''}${diff}</span>`;
}
</script>
@endpush
