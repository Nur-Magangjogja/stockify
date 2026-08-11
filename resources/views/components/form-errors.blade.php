{{-- Form Validation Errors — menampilkan semua error dari $errors --}}

@if($errors->any())
<div class="p-4 mb-4 rounded-lg bg-red-900/30 border border-red-700 text-red-300" role="alert">
    <p class="text-sm font-semibold mb-1">Terdapat kesalahan:</p>
    <ul class="list-disc list-inside text-sm space-y-1">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
