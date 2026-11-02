<?php

namespace Modules\Library\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\Library\Exceptions\AuthorHasBooksException;
use Modules\Library\Models\Author;
use Modules\Library\Requests\StoreAuthorRequest;
use Modules\Library\Requests\UpdateAuthorRequest;

class AuthorController extends Controller
{
    public function index(): View
    {
        $authors = Author::withCount('books')->latest()->paginate(10);

        return view('library.authors.index', compact('authors'));
    }

    public function create(): View
    {
        return view('library.authors.create');
    }

    public function store(StoreAuthorRequest $request): RedirectResponse
    {
        Author::create($request->validated());

        return redirect()
            ->route('library.authors.index')
            ->with('success', 'Penulis berhasil ditambahkan.');
    }

    public function edit(Author $author): View
    {
        return view('library.authors.edit', compact('author'));
    }

    public function update(UpdateAuthorRequest $request, Author $author): RedirectResponse
    {
        $author->update($request->validated());

        return redirect()
            ->route('library.authors.index')
            ->with('success', 'Penulis berhasil diperbarui.');
    }

    public function destroy(Author $author): RedirectResponse
    {
        $bookCount = $author->books()->count();

        if ($bookCount > 0) {
            throw new AuthorHasBooksException($author->name, $bookCount);
        }

        $author->delete();

        return redirect()
            ->route('library.authors.index')
            ->with('success', 'Penulis berhasil dihapus.');
    }
}
