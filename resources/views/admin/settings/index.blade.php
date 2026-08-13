@extends('layouts.app')

@section('title', 'Pengaturan Umum')
@section('page-title', 'Pengaturan Aplikasi')
@section('page-subtitle', 'Sesuaikan nama dan logo aplikasi Anda')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 space-y-6">
            
            {{-- App Name --}}
            <div>
                <label for="app_name" class="block text-sm font-medium text-gray-300 mb-1.5">Nama Aplikasi <span class="text-red-400">*</span></label>
                <input type="text" name="app_name" id="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'Stockify') }}" required
                    class="input-field w-full text-sm rounded-lg px-3 py-2.5"
                    placeholder="Contoh: GudangKu">
                @error('app_name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- App Logo --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Logo Aplikasi</label>
                
                <div class="flex items-start gap-6">
                    {{-- Preview Area --}}
                    <div class="shrink-0">
                        <div class="w-24 h-24 rounded-xl border border-gray-600 bg-gray-900 flex items-center justify-center overflow-hidden">
                            @if(!empty($settings['app_logo']))
                                <img src="{{ asset($settings['app_logo']) }}" alt="App Logo" id="logo-preview" class="w-full h-full object-contain p-2">
                            @else
                                <svg id="default-logo-icon" class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                                </svg>
                                <img src="" alt="App Logo" id="logo-preview" class="w-full h-full object-contain p-2 hidden">
                            @endif
                        </div>
                    </div>

                    {{-- Upload Area --}}
                    <div class="flex-1">
                        <div class="flex items-center justify-center w-full">
                            <label for="app_logo" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-900/50 hover:bg-gray-800 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-6 h-6 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="text-xs text-gray-400"><span class="font-semibold text-blue-400">Klik untuk unggah</span> logo baru</p>
                                </div>
                                <input id="app_logo" name="app_logo" type="file" class="hidden" accept="image/png, image/jpeg, image/svg+xml" />
                            </label>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Format yang didukung: PNG, JPG, SVG. Maks 2MB.</p>
                        @error('app_logo')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-700">
                <button type="submit"
                    class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-blue-900/20">
                    Simpan Pengaturan
                </button>
            </div>
            
        </div>
    </form>
</div>

<div class="max-w-2xl mt-12">
    <div class="bg-red-900/10 border border-red-900/50 rounded-xl p-5">
        <div class="flex justify-between items-center gap-4">
            <div>
                <h3 class="text-lg font-medium text-red-500 mb-1">Manajemen Kelola Data (Danger Zone)</h3>
                <p class="text-sm text-gray-400">Area ini berisi pengaturan khusus untuk menghapus data secara masal pada sistem.</p>
            </div>
            <button type="button" id="unlock-danger-zone" class="shrink-0 px-4 py-2 bg-red-600/20 hover:bg-red-600 text-red-500 hover:text-white text-sm font-medium rounded-lg border border-red-600/50 transition-all">
                Buka Pengaturan
            </button>
        </div>

        <div id="danger-zone-content" class="hidden mt-6 pt-6 border-t border-red-900/50 space-y-6">
            <div class="bg-red-900/20 p-4 rounded-lg border border-red-800/50 flex gap-3 items-start">
                <svg class="w-6 h-6 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-sm text-red-400 leading-relaxed"><strong>PERINGATAN:</strong> Aksi di bawah ini bersifat permanen dan tidak dapat dibatalkan. Pastikan Anda telah melakukan *backup* atau ekspor laporan terlebih dahulu.</p>
            </div>

            <!-- Transaction Data Management -->
            <div class="bg-gray-800 border border-red-900/50 rounded-xl p-5 space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-white mb-1">Manajemen Data Transaksi</h3>
                    <p class="text-sm text-gray-400">Hapus riwayat transaksi stok lama untuk meringankan performa database. Sistem akan otomatis meringkas sisa stok menjadi transaksi "Saldo Awal" agar jumlah stok saat ini tetap akurat.</p>
                </div>

                <form action="{{ route('admin.settings.reset-transactions') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dan mengkompres data transaksi pada periode ini? Proses ini tidak dapat dibatalkan.')">
                    @csrf
                    <div class="flex flex-col xl:flex-row gap-3 items-start xl:items-end">
                        <div class="w-full xl:w-1/4">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Tipe Pemilihan</label>
                            <select name="delete_type" id="delete_type" class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                                <option value="date_range">Rentang Tanggal</option>
                                <option value="exact_month">Bulan Spesifik</option>
                            </select>
                        </div>
                        <div class="flex-1 w-full" id="container_date">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div class="w-full">
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Dari Tanggal</label>
                                    <input type="date" name="start_date" id="start_date" max="{{ date('Y-m-d') }}" required
                                        class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                                </div>
                                <div class="w-full">
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Sampai Tanggal</label>
                                    <input type="date" name="end_date" id="end_date" max="{{ date('Y-m-d') }}" required
                                        class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 w-full hidden" id="container_month">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Pilih Bulan</label>
                            <input type="month" name="specific_month" id="specific_month" max="{{ date('Y-m') }}"
                                class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                        </div>
                        <button type="submit" class="w-full xl:w-auto px-5 py-2.5 bg-red-600/90 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-red-900/20 whitespace-nowrap mt-3 xl:mt-0">
                            Kompres & Hapus Data
                        </button>
                    </div>
                </form>
            </div>

            <!-- Activity Data Management -->
            <div class="bg-gray-800 border border-red-900/50 rounded-xl p-5 space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-white mb-1">Manajemen Data Aktivitas (Log Pengguna)</h3>
                    <p class="text-sm text-gray-400">Hapus riwayat aktivitas atau log aksi pengguna pada sistem (seperti log pembuatan produk, login, dll) untuk meringankan performa database.</p>
                </div>

                <form action="{{ route('admin.settings.reset-activities') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data aktivitas pada periode ini? Proses ini tidak dapat dibatalkan.')">
                    @csrf
                    <div class="flex flex-col xl:flex-row gap-3 items-start xl:items-end">
                        <div class="w-full xl:w-1/4">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Tipe Pemilihan</label>
                            <select name="activity_delete_type" id="activity_delete_type" class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                                <option value="date_range">Rentang Tanggal</option>
                                <option value="exact_month">Bulan Spesifik</option>
                            </select>
                        </div>
                        <div class="flex-1 w-full" id="activity_container_date">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div class="w-full">
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Dari Tanggal</label>
                                    <input type="date" name="activity_start_date" id="activity_start_date" max="{{ date('Y-m-d') }}" required
                                        class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                                </div>
                                <div class="w-full">
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Sampai Tanggal</label>
                                    <input type="date" name="activity_end_date" id="activity_end_date" max="{{ date('Y-m-d') }}" required
                                        class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 w-full hidden" id="activity_container_month">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Pilih Bulan</label>
                            <input type="month" name="activity_specific_month" id="activity_specific_month" max="{{ date('Y-m') }}"
                                class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                        </div>
                        <button type="submit" class="w-full xl:w-auto px-5 py-2.5 bg-red-600/90 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-red-900/20 whitespace-nowrap mt-3 xl:mt-0">
                            Hapus Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


