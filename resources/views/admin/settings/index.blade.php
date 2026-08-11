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
                    class="w-full bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
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
@endsection

@push('scripts')
<script>
    document.getElementById('app_logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logo-preview');
                const defaultIcon = document.getElementById('default-logo-icon');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                
                if (defaultIcon) {
                    defaultIcon.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
