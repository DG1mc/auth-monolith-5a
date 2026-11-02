<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit Buku</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 text-gray-900 shadow-sm sm:rounded-lg">
                <x-flash-message />

                <form action="{{ route('library.books.update', $book) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('library.books._form')
                    <x-primary-button>Perbarui</x-primary-button>
                    <a href="{{ route('library.books.index') }}" class="ms-3 text-sm text-gray-600 underline">Batal</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
