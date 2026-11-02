<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Detail Produk</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <x-flash-message />

            <div class="overflow-hidden bg-white p-6 text-gray-900 shadow-sm sm:rounded-lg">
                <dl class="space-y-2">
                    <div><dt class="text-sm text-gray-500">Nama</dt><dd>{{ $product->name }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Slug</dt><dd>{{ $product->slug }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Harga</dt><dd>Rp {{ number_format($product->price, 2, ',', '.') }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Stok</dt><dd>{{ $product->stock }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Deskripsi</dt><dd>{{ $product->description ?? '-' }}</dd></div>
                </dl>

                <form action="{{ route('products.reduce-stock', $product) }}" method="POST" class="mt-6 border-t pt-4">
                    @csrf
                    <x-input-label for="quantity" value="Kurangi stok sebanyak" />
                    <div class="mt-1 flex items-center gap-3">
                        <x-text-input id="quantity" name="quantity" type="text" inputmode="numeric" class="w-32" :value="old('quantity', 1)" />
                        <x-primary-button>Kurangi Stok</x-primary-button>
                    </div>
                    <x-input-error :messages="$errors->get('quantity')" class="mt-1" />
                </form>

                <a href="{{ route('products.index') }}" class="mt-6 inline-block text-sm text-gray-600 underline">&larr; Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
