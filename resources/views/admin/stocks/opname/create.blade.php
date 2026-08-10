@extends('layouts.app')
@section('title', 'Buat Stock Opname')
@section('page-title', 'Buat Stock Opname Baru')

@section('content')
<div class="max-w-lg">
    @php $prefix = auth()->user()->isAdmin() ? 'admin' : 'manager'; @endphp
    <form action="{{ route("{$prefix}.stocks.opname.store") }}" method="POST">
        @csrf
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 space-y-4">
            <div class="p-3 bg-blue-900/20 border border-blue-700/40 rounded-lg text-sm text-blue-300">
                <p>Stock opname akan memuat <strong>semua produk aktif</strong> secara otomatis. Anda dapat mengisi jumlah stok fisik setelah membuat opname.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Tanggal Opname <span class="text-red-400">*</span></label>
                <input type="date" name="opname_date" value="{{ old('opname_date', date('Y-m-d')) }}" required
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Catatan</label>
                <textarea name="notes" rows="3"
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Keterangan atau catatan stock opname (opsional)">{{ old('notes') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route("{$prefix}.stocks.opname.index") }}"
                   class="flex-1 py-2.5 text-center bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm font-medium rounded-lg transition-all">Batal</a>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all">Buat Opname</button>
            </div>
        </div>
    </form>
</div>
@endsection
