<?php

namespace Modules\Library\Exceptions;

use Modules\Shared\Exceptions\BusinessRuleException;

class AuthorHasBooksException extends BusinessRuleException
{
    public function __construct(protected string $authorName, protected int $bookCount)
    {
        parent::__construct(
            "Penulis '{$authorName}' tidak dapat dihapus karena masih memiliki {$bookCount} buku."
        );
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getBookCount(): int
    {
        return $this->bookCount;
    }

    public function errorCode(): string
    {
        return 'author_has_books';
    }

    /**
     * @return array<string, mixed>
     */
    public function details(): array
    {
        return [
            'author' => $this->authorName,
            'book_count' => $this->bookCount,
        ];
    }
}
