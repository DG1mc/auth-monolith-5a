<?php

use App\Models\User;
use Modules\Library\Models\Author;
use Modules\Library\Models\Book;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guest is redirected to login', function () {
    app('auth')->forgetGuards();

    $this->get(route('library.authors.index'))->assertRedirect(route('login'));
});

test('author pages can be rendered', function () {
    $author = Author::factory()->create();

    $this->get(route('library.authors.index'))->assertOk()->assertSee($author->name);
    $this->get(route('library.authors.create'))->assertOk();
    $this->get(route('library.authors.edit', $author))->assertOk();
});

test('store rejects empty form', function () {
    $this->from(route('library.authors.create'))
        ->post(route('library.authors.store'), [])
        ->assertRedirect(route('library.authors.create'))
        ->assertSessionHasErrors([
            'name' => 'Nama penulis wajib diisi.',
            'email' => 'Email penulis wajib diisi.',
        ]);
});

test('store rejects invalid email, duplicate email, long name and long bio', function () {
    Author::factory()->create(['email' => 'ada@example.com']);

    $this->post(route('library.authors.store'), ['name' => 'A', 'email' => 'bukan-email'])
        ->assertSessionHasErrors(['email' => 'Format email tidak valid.']);

    $this->post(route('library.authors.store'), ['name' => 'A', 'email' => 'ada@example.com'])
        ->assertSessionHasErrors(['email' => 'Email sudah digunakan penulis lain.']);

    $this->post(route('library.authors.store'), [
        'name' => str_repeat('a', 256), 'email' => 'baru@example.com', 'bio' => str_repeat('b', 1001),
    ])->assertSessionHasErrors(['name' => 'Nama penulis maksimal 255 karakter.', 'bio' => 'Bio maksimal 1000 karakter.']);
});

test('store creates author with valid data and nullable bio', function () {
    $this->post(route('library.authors.store'), ['name' => 'Tere Liye', 'email' => 'tere@example.com'])
        ->assertRedirect(route('library.authors.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('authors', ['email' => 'tere@example.com', 'bio' => null]);
});

test('update allows keeping own email', function () {
    $author = Author::factory()->create(['email' => 'saya@example.com']);

    $this->put(route('library.authors.update', $author), ['name' => 'Nama Baru', 'email' => 'saya@example.com'])
        ->assertRedirect(route('library.authors.index'))
        ->assertSessionHas('success');

    expect($author->fresh()->name)->toBe('Nama Baru');
});

test('deleting an author who still has books throws AuthorHasBooksException', function () {
    $author = Author::factory()->create(['name' => 'Penulis A']);
    Book::factory()->count(2)->for($author)->create();

    $this->from(route('library.authors.index'))
        ->delete(route('library.authors.destroy', $author))
        ->assertRedirect(route('library.authors.index'))
        ->assertSessionHas('error', "Penulis 'Penulis A' tidak dapat dihapus karena masih memiliki 2 buku.");

    $this->assertDatabaseHas('authors', ['id' => $author->id]);
});

test('author delete returns json 422 for json requests', function () {
    $author = Author::factory()->create();
    Book::factory()->for($author)->create();

    $this->deleteJson(route('library.authors.destroy', $author))
        ->assertStatus(422)
        ->assertJson(['error' => 'author_has_books', 'details' => ['book_count' => 1]]);
});

test('an author without books can be deleted', function () {
    $author = Author::factory()->create();

    $this->delete(route('library.authors.destroy', $author))
        ->assertRedirect(route('library.authors.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('authors', ['id' => $author->id]);
});
