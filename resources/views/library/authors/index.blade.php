<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Daftar Penulis</h2>
            <a href="{{ route('library.authors.create') }}" class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700">+ Tambah Penulis</a>
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
                                <th class="px-6 py-3 text-left">Nama</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-center">Jumlah Buku</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600">
                            @forelse ($authors as $author)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-3">{{ $author->name }}</td>
                                    <td class="px-6 py-3">{{ $author->email }}</td>
                                    <td class="px-6 py-3 text-center">{{ $author->books_count }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('library.authors.edit', $author) }}" class="font-semibold text-blue-500 hover:text-blue-700">Edit</a>
                                            <form action="{{ route('library.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('Hapus penulis ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-semibold text-red-500 hover:text-red-700">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada penulis.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">{{ $authors->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
