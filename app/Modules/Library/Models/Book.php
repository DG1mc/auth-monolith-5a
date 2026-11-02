<?php

namespace Modules\Library\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'isbn',
        'published_year',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'published_year' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * @return BelongsTo<Author, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return HasMany<Loan, $this>
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    protected static function newFactory(): BookFactory
    {
        return BookFactory::new();
    }
}
