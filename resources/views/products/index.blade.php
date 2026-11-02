<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Daftar Produk</h2>
            <a href="{{ route('products.create') }}" class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700">+ Tambah Produk</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-flash-message />

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900">
                    <table class="min-w-full border border-gray-200 bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-sm uppercase leading-normal text-gray-600">
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Nama Produk</th>
                                <th class="px-6 py-3 text-left">Slug</th>
                                <th class="px-6 py-3 text-right">Harga</th>
                                <th class="px-6 py-3 text-center">Stok</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600">
                            @forelse ($products as $product)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-3">{{ $products->firstItem() + $loop->index }}</td>
                                    <td class="px-6 py-3">{{ $product->name }}</td>
                                    <td class="px-6 py-3">{{ $product->slug }}</td>
                                    <td class="px-6 py-3 text-right">Rp {{ number_format($product->price, 2, ',', '.') }}</td>
                                    <td class="px-6 py-3 text-center">{{ $product->stock }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('products.show', $product) }}" class="font-semibold text-gray-600 hover:text-gray-900">Detail</a>
                                            <a href="{{ route('products.edit', $product) }}" class="font-semibold text-blue-500 hover:text-blue-700">Edit</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-semibold text-red-500 hover:text-red-700">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data produk. Silakan tambah produk terlebih dahulu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
