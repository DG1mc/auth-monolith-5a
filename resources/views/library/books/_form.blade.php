@php($book = $book ?? null)

<div class="mb-4">
    <x-input-label for="title" value="Judul" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $book?->title)" />
    <x-input-error :messages="$errors->get('title')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="isbn" value="ISBN (10 atau 13 digit)" />
    <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full" :value="old('isbn', $book?->isbn)" />
    <x-input-error :messages="$errors->get('isbn')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="author_id" value="Penulis" />
    <select id="author_id" name="author_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        <option value="">-- Pilih penulis --</option>
        @foreach ($authors as $author)
            <option value="{{ $author->id }}" @selected((string) old('author_id', $book?->author_id) === (string) $author->id)>{{ $author->name }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('author_id')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="published_year" value="Tahun Terbit" />
    <x-text-input id="published_year" name="published_year" type="text" inputmode="numeric" class="mt-1 block w-full" :value="old('published_year', $book?->published_year)" />
    <x-input-error :messages="$errors->get('published_year')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="price" value="Harga" />
    <x-text-input id="price" name="price" type="text" inputmode="decimal" class="mt-1 block w-full" :value="old('price', $book?->price)" />
    <x-input-error :messages="$errors->get('price')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="stock" value="Stok" />
    <x-text-input id="stock" name="stock" type="text" inputmode="numeric" class="mt-1 block w-full" :value="old('stock', $book?->stock)" />
    <x-input-error :messages="$errors->get('stock')" class="mt-1" />
</div>
