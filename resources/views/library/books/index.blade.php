<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Daftar Buku</h2>
            <a href="{{ route('library.books.create') }}" class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700">+ Tambah Buku</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-flash-message />

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900">
                    <table class="min-w-full border border-gray-200 bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-sm uppercase text-gray-600">
                                <th class="px-6 py-3 text-left">Judul</th>
                                <th class="px-6 py-3 text-left">ISBN</th>
                                <th class="px-6 py-3 text-left">Penulis</th>
                                <th class="px-6 py-3 text-center">Tahun</th>
                                <th class="px-6 py-3 text-right">Harga</th>
                                <th class="px-6 py-3 text-center">Stok</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600">
                            @forelse ($books as $book)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-3">{{ $book->title }}</td>
                                    <td class="px-6 py-3">{{ $book->isbn }}</td>
                                    <td class="px-6 py-3">{{ $book->author->name }}</td>
                                    <td class="px-6 py-3 text-center">{{ $book->published_year }}</td>
                                    <td class="px-6 py-3 text-right">Rp {{ number_format($book->price, 2, ',', '.') }}</td>
                                    <td class="px-6 py-3 text-center">{{ $book->stock }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <form action="{{ route('library.loans.store', $book) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="font-semibold text-green-600 hover:text-green-800">Pinjam</button>
                                            </form>
                                            <a href="{{ route('library.books.edit', $book) }}" class="font-semibold text-blue-500 hover:text-blue-700">Edit</a>
                                            <form action="{{ route('library.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Hapus buku ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-semibold text-red-500 hover:text-red-700">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada buku.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">{{ $books->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
