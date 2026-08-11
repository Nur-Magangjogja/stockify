{{-- Flash Alert — menampilkan session('success') dan session('error') --}}

@if(session('success'))
<div id="alert-success"
     class="flex items-start gap-3 p-4 mb-4 rounded-lg bg-green-900/30 border border-green-700 text-green-300"
     role="alert">
    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <path fill-rule="evenodd"
            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
    </svg>
    <span class="text-sm">{!! session('success') !!}</span>
    <button type="button"
            class="ml-auto text-green-400 hover:text-white"
            data-dismiss-target="#alert-success"
            aria-label="Tutup notifikasi">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
        </svg>
    </button>
</div>
@endif

@if(session('error'))
<div id="alert-error"
     class="flex items-start gap-3 p-4 mb-4 rounded-lg bg-red-900/30 border border-red-700 text-red-300"
     role="alert">
    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <path fill-rule="evenodd"
            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
    </svg>
    <span class="text-sm">{!! session('error') !!}</span>
    <button type="button"
            class="ml-auto text-red-400 hover:text-white"
            data-dismiss-target="#alert-error"
            aria-label="Tutup notifikasi">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
        </svg>
    </button>
</div>
@endif
