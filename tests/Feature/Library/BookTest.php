<?php

use App\Models\User;
use Modules\Library\Models\Author;
use Modules\Library\Models\Book;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
    $this->author = Author::factory()->create();
});

function validBookData(array $overrides = []): array
{
    return array_merge([
        'title' => 'Bumi Manusia',
        'isbn' => '9789799731234',
        'published_year' => 1980,
        'price' => 98000,
        'stock' => 5,
        'author_id' => test()->author->id,
    ], $overrides);
}

test('book pages can be rendered', function () {
    $book = Book::factory()->for($this->author)->create();

    $this->get(route('library.books.index'))->assertOk()->assertSee($book->title);
    $this->get(route('library.books.create'))->assertOk();
    $this->get(route('library.books.edit', $book))->assertOk();
});

test('store rejects empty form with indonesian messages', function () {
    $this->from(route('library.books.create'))
        ->post(route('library.books.store'), [])
        ->assertRedirect(route('library.books.create'))
        ->assertSessionHasErrors([
            'title' => 'Judul buku wajib diisi.',
            'isbn' => 'ISBN wajib diisi.',
            'published_year' => 'Tahun terbit wajib diisi.',
            'price' => 'Harga wajib diisi.',
            'stock' => 'Stok wajib diisi.',
            'author_id' => 'Penulis wajib dipilih.',
        ]);
});

test('store rejects invalid isbn format', function () {
    foreach (['123', 'ABCDEFGHIJ', '9770000000000', '97897997312345'] as $isbn) {
        $this->post(route('library.books.store'), validBookData(['isbn' => $isbn]))
            ->assertSessionHasErrors(['isbn' => 'Format ISBN tidak valid. Gunakan ISBN-10 atau ISBN-13.']);
    }
});

test('store accepts isbn-10 with X and hyphenated isbn-13, storing the normalized value', function () {
    $this->post(route('library.books.store'), validBookData(['isbn' => '0-8044-2957-X']))
        ->assertSessionHasNoErrors();
    $this->post(route('library.books.store'), validBookData(['isbn' => '978-979-973-123-4', 'title' => 'Lain']))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('books', ['isbn' => '080442957X']);
    $this->assertDatabaseHas('books', ['isbn' => '9789799731234']);
});

test('store rejects duplicate isbn even when written with hyphens', function () {
    Book::factory()->for($this->author)->create(['isbn' => '9789799731234']);

    $this->post(route('library.books.store'), validBookData(['isbn' => '978-979-973-123-4']))
        ->assertSessionHasErrors(['isbn' => 'ISBN sudah terdaftar.']);
});

test('store rejects published year outside 1900 and the current year', function () {
    $this->post(route('library.books.store'), validBookData(['published_year' => 1899]))
        ->assertSessionHasErrors(['published_year' => 'Tahun terbit minimal 1900.']);

    $this->post(route('library.books.store'), validBookData(['published_year' => now()->year + 1]))
        ->assertSessionHasErrors('published_year');

    $this->post(route('library.books.store'), validBookData(['published_year' => 'dua ribu']))
        ->assertSessionHasErrors(['published_year' => 'Tahun terbit harus berupa bilangan bulat.']);
});

test('store rejects negative price, negative stock, long title and unknown author', function () {
    $this->post(route('library.books.store'), validBookData([
        'price' => -5, 'stock' => -1, 'title' => str_repeat('x', 256), 'author_id' => 9999,
    ]))->assertSessionHasErrors([
        'price' => 'Harga tidak boleh negatif.',
        'stock' => 'Stok tidak boleh negatif.',
        'title' => 'Judul buku maksimal 255 karakter.',
        'author_id' => 'Penulis yang dipilih tidak ditemukan.',
    ]);
});

test('store creates a book with valid data', function () {
    $this->post(route('library.books.store'), validBookData())
        ->assertRedirect(route('library.books.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('books', ['title' => 'Bumi Manusia', 'author_id' => $this->author->id]);
});

test('update allows keeping own isbn but rejects another books isbn', function () {
    $book = Book::factory()->for($this->author)->create(['isbn' => '9789799731234']);
    Book::factory()->for($this->author)->create(['isbn' => '9789799731241']);

    $this->put(route('library.books.update', $book), validBookData(['title' => 'Judul Baru']))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('library.books.index'));

    expect($book->fresh()->title)->toBe('Judul Baru');

    $this->put(route('library.books.update', $book), validBookData(['isbn' => '9789799731241']))
        ->assertSessionHasErrors(['isbn' => 'ISBN sudah terdaftar.']);
});
