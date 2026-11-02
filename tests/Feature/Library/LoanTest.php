<?php

use App\Models\User;
use Modules\Library\Models\Book;
use Modules\Library\Models\Loan;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('borrowing a book with stock 0 throws BookNotAvailableException', function () {
    $book = Book::factory()->outOfStock()->create(['title' => 'Buku Habis']);

    $this->from(route('library.books.index'))
        ->post(route('library.loans.store', $book))
        ->assertRedirect(route('library.books.index'))
        ->assertSessionHas('error', "Buku 'Buku Habis' tidak tersedia (stok habis) dan tidak dapat dipinjam.");

    expect(Loan::count())->toBe(0)
        ->and($book->fresh()->stock)->toBe(0);
});

test('borrowing an unavailable book returns json 422 for json requests', function () {
    $book = Book::factory()->outOfStock()->create();

    $this->postJson(route('library.loans.store', $book))
        ->assertStatus(422)
        ->assertJson(['error' => 'book_not_available']);
});

test('borrowing a book decrements stock and creates a loan', function () {
    $book = Book::factory()->create(['stock' => 2]);

    $this->post(route('library.loans.store', $book))
        ->assertRedirect(route('library.loans.index'))
        ->assertSessionHas('success');

    expect($book->fresh()->stock)->toBe(1);
    $this->assertDatabaseHas('loans', ['book_id' => $book->id, 'user_id' => $this->user->id, 'returned_at' => null]);
});

test('the last copy can be borrowed once, the next attempt fails', function () {
    $book = Book::factory()->create(['stock' => 1]);

    $this->post(route('library.loans.store', $book))->assertSessionHas('success');
    $this->post(route('library.loans.store', $book))->assertSessionHas('error');

    expect(Loan::count())->toBe(1);
});

test('returning a book increments stock and cannot be done twice', function () {
    $book = Book::factory()->create(['stock' => 1]);
    $this->post(route('library.loans.store', $book));
    $loan = Loan::firstOrFail();

    $this->patch(route('library.loans.return', $loan))->assertSessionHas('success');
    expect($book->fresh()->stock)->toBe(1);

    $this->patch(route('library.loans.return', $loan))->assertSessionHas('error');
    expect($book->fresh()->stock)->toBe(1);
});

test('a user cannot return another users loan', function () {
    $book = Book::factory()->create();
    $loan = Loan::create(['book_id' => $book->id, 'user_id' => User::factory()->create()->id, 'borrowed_at' => now()]);

    $this->patch(route('library.loans.return', $loan))->assertForbidden();
});

test('loan page lists only my loans', function () {
    $mine = Book::factory()->create(['title' => 'Punya Saya']);
    $theirs = Book::factory()->create(['title' => 'Punya Orang']);
    Loan::create(['book_id' => $mine->id, 'user_id' => $this->user->id, 'borrowed_at' => now()]);
    Loan::create(['book_id' => $theirs->id, 'user_id' => User::factory()->create()->id, 'borrowed_at' => now()]);

    $this->get(route('library.loans.index'))
        ->assertOk()
        ->assertSee('Punya Saya')
        ->assertDontSee('Punya Orang');
});
