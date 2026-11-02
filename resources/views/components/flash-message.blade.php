@if (session('success'))
    <div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700" role="alert">
        <p class="mb-1 font-semibold">Terdapat kesalahan pada isian formulir:</p>
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
