<?php

namespace Modules\Library\Exceptions;

use Modules\Shared\Exceptions\BusinessRuleException;

class BookNotAvailableException extends BusinessRuleException
{
    public function __construct(protected string $bookTitle)
    {
        parent::__construct("Buku '{$bookTitle}' tidak tersedia (stok habis) dan tidak dapat dipinjam.");
    }

    public function getBookTitle(): string
    {
        return $this->bookTitle;
    }

    public function errorCode(): string
    {
        return 'book_not_available';
    }

    /**
     * @return array<string, mixed>
     */
    public function details(): array
    {
        return ['book' => $this->bookTitle];
    }
}
