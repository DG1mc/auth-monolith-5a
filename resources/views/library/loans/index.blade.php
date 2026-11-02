<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Peminjaman Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-flash-message />

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900">
                    <table class="min-w-full border border-gray-200 bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-sm uppercase text-gray-600">
                                <th class="px-6 py-3 text-left">Buku</th>
                                <th class="px-6 py-3 text-left">Penulis</th>
                                <th class="px-6 py-3 text-left">Dipinjam</th>
                                <th class="px-6 py-3 text-left">Dikembalikan</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600">
                            @forelse ($loans as $loan)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-3">{{ $loan->book->title }}</td>
                                    <td class="px-6 py-3">{{ $loan->book->author->name }}</td>
                                    <td class="px-6 py-3">{{ $loan->borrowed_at->format('d-m-Y H:i') }}</td>
                                    <td class="px-6 py-3">{{ $loan->returned_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                    <td class="px-6 py-3 text-center">
                                        @if ($loan->returned_at === null)
                                            <form action="{{ route('library.loans.return', $loan) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="font-semibold text-blue-500 hover:text-blue-700">Kembalikan</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada peminjaman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">{{ $loans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
