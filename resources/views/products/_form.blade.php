@php($product = $product ?? null)

<div class="mb-4">
    <x-input-label for="name" value="Nama Produk" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product?->name)" />
    <x-input-error :messages="$errors->get('name')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="slug" value="Slug" />
    <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $product?->slug)" />
    <x-input-error :messages="$errors->get('slug')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="price" value="Harga" />
    <x-text-input id="price" name="price" type="text" inputmode="decimal" class="mt-1 block w-full" :value="old('price', $product?->price)" />
    <x-input-error :messages="$errors->get('price')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="stock" value="Stok" />
    <x-text-input id="stock" name="stock" type="text" inputmode="numeric" class="mt-1 block w-full" :value="old('stock', $product?->stock)" />
    <x-input-error :messages="$errors->get('stock')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="description" value="Deskripsi" />
    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $product?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-1" />
</div>
