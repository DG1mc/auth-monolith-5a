@php($author = $author ?? null)

<div class="mb-4">
    <x-input-label for="name" value="Nama" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $author?->name)" />
    <x-input-error :messages="$errors->get('name')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="text" class="mt-1 block w-full" :value="old('email', $author?->email)" />
    <x-input-error :messages="$errors->get('email')" class="mt-1" />
</div>

<div class="mb-4">
    <x-input-label for="bio" value="Bio" />
    <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('bio', $author?->bio) }}</textarea>
    <x-input-error :messages="$errors->get('bio')" class="mt-1" />
</div>
