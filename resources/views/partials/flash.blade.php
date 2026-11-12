@if (session('success'))
    <div class="p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="p-3 rounded bg-red-100 text-red-800">{{ $errors->first() }}</div>
@endif
