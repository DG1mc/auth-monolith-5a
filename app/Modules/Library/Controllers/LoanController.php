<?php

namespace Modules\Library\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Library\Exceptions\BookNotAvailableException;
use Modules\Library\Models\Book;
use Modules\Library\Models\Loan;

class LoanController extends Controller
{
    public function index(Request $request): View
    {
        $loans = Loan::with('book.author')
            ->where('user_id', $request->user()->id)
            ->latest('borrowed_at')
            ->paginate(10);

        return view('library.loans.index', compact('loans'));
    }

    /**
     * Pinjam buku. Stok divalidasi di dalam transaksi (dengan lock) agar aman dari race condition.
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $loan = DB::transaction(function () use ($request, $book): Loan {
            $lockedBook = Book::whereKey($book->id)->lockForUpdate()->firstOrFail();

            if ($lockedBook->stock < 1) {
                throw new BookNotAvailableException($lockedBook->title);
            }

            $lockedBook->decrement('stock');

            return Loan::create([
                'book_id' => $lockedBook->id,
                'user_id' => $request->user()->id,
                'borrowed_at' => now(),
            ]);
        });

        Log::info('Buku dipinjam.', ['loan_id' => $loan->id, 'book_id' => $book->id, 'user_id' => $request->user()->id]);

        return redirect()
            ->route('library.loans.index')
            ->with('success', "Buku '{$book->title}' berhasil dipinjam.");
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        abort_unless($loan->user_id === $request->user()->id, 403, 'Anda tidak berhak mengubah peminjaman ini.');

        if ($loan->returned_at !== null) {
            return redirect()
                ->route('library.loans.index')
                ->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($loan): void {
            $loan->update(['returned_at' => now()]);
            $loan->book()->increment('stock');
        });

        Log::info('Buku dikembalikan.', ['loan_id' => $loan->id, 'book_id' => $loan->book_id]);

        return redirect()
            ->route('library.loans.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }
}
