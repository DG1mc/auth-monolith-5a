<?php

namespace Modules\Library\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\Library\Models\Author;
use Modules\Library\Models\Book;
use Modules\Library\Requests\StoreBookRequest;
use Modules\Library\Requests\UpdateBookRequest;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::with('author')->latest()->paginate(10);

        return view('library.books.index', compact('books'));
    }

    public function create(): View
    {
        $authors = Author::orderBy('name')->get();

        return view('library.books.create', compact('authors'));
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        Book::create($request->validated());

        return redirect()
            ->route('library.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book): View
    {
        $authors = Author::orderBy('name')->get();

        return view('library.books.edit', compact('book', 'authors'));
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $book->update($request->validated());

        return redirect()
            ->route('library.books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()
            ->route('library.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
