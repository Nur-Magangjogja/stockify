@extends('layouts.app')
@section('title', 'Stock Opname')
@section('page-title', 'Stock Opname')
@section('page-subtitle', 'Bandingkan stok sistem dengan stok fisik')
@section('content')
@php $prefix = auth()->user()->isAdmin() ? 'admin' : 'manager'; @endphp
<form action="{{ route("{$prefix}.stocks.opname.store") }}" method="POST">
    @csrf
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 mb-5">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Tanggal Opname</label>
                <input type="date" name="opname_date" value="{{ old('opname_date', date('Y-m-d')) }}" required class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Catatan</label>
                <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Opsional" class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5">
            </div>
        </div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="border-b border-gray-700"><tr><th class="px-5 py-3 text-gray-400">Produk</th><th class="px-4 py-3 text-center text-gray-400">Stok Sistem</th><th class="px-4 py-3 text-center text-gray-400">Stok Fisik</th><th class="px-4 py-3 text-center text-gray-400">Selisih</th></tr></thead>
                <tbody class="divide-y divide-gray-700">
                @forelse($products as $product)
                    <tr><td class="px-5 py-3"><p class="text-white font-medium">{{ $product->name }}</p><p class="text-xs text-gray-400">{{ $product->sku }}</p></td><td class="px-4 py-3 text-center text-gray-300">{{ $product->stock }}</td><td class="px-4 py-3 text-center"><input type="number" name="stocks[{{ $product->id }}]" value="{{ old('stocks.' . $product->id, $product->stock) }}" min="0" required data-system="{{ $product->stock }}" oninput="updateDifference(this)" class="w-24 bg-gray-900 border border-gray-600 text-center text-white rounded-lg px-2 py-1.5"></td><td class="px-4 py-3 text-center text-gray-400" data-difference>0</td></tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">Belum ada produk.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($products->isNotEmpty())
        <div class="flex justify-end px-5 py-4 border-t border-gray-700"><button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan Penyesuaian</button></div>
        @endif
    </div>
</form>
@endsection
@push('scripts')
<script>
function updateDifference(input) {
    const difference = Number(input.value || 0) - Number(input.dataset.system || 0);
    const target = input.closest('tr').querySelector('[data-difference]');
    target.textContent = difference > 0 ? '+' + difference : difference;
    target.className = 'px-4 py-3 text-center ' + (difference > 0 ? 'text-green-400' : difference < 0 ? 'text-red-400' : 'text-gray-400');
}
document.querySelectorAll('[data-system]').forEach(updateDifference);
</script>
@endpush
