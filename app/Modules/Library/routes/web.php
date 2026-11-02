<?php

use Illuminate\Support\Facades\Route;
use Modules\Library\Controllers\AuthorController;
use Modules\Library\Controllers\BookController;
use Modules\Library\Controllers\LoanController;

Route::middleware(['web', 'auth'])
    ->prefix('library')
    ->name('library.')
    ->group(function () {
        Route::resource('authors', AuthorController::class)->except('show');
        Route::resource('books', BookController::class)->except('show');

        Route::get('loans', [LoanController::class, 'index'])->name('loans.index');
        Route::post('books/{book}/borrow', [LoanController::class, 'store'])->name('loans.store');
        Route::patch('loans/{loan}/return', [LoanController::class, 'update'])->name('loans.return');
    });
