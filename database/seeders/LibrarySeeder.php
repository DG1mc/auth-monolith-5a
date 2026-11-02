<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Library\Models\Author;
use Modules\Library\Models\Book;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        $pramoedya = Author::create([
            'name' => 'Pramoedya Ananta Toer',
            'email' => 'pramoedya@example.com',
            'bio' => 'Sastrawan Indonesia.',
        ]);

        Author::create([
            'name' => 'Penulis Tanpa Buku',
            'email' => 'kosong@example.com',
            'bio' => null,
        ]);

        Book::create([
            'author_id' => $pramoedya->id,
            'title' => 'Bumi Manusia',
            'isbn' => '9789799731234',
            'published_year' => 1980,
            'price' => 98000,
            'stock' => 5,
        ]);

        Book::create([
            'author_id' => $pramoedya->id,
            'title' => 'Anak Semua Bangsa (stok habis)',
            'isbn' => '9789799731241',
            'published_year' => 1980,
            'price' => 95000,
            'stock' => 0,
        ]);
    }
}
