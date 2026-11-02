<?php

namespace Modules\Products\Exceptions;

use Modules\Shared\Exceptions\BusinessRuleException;

class InsufficientStockException extends BusinessRuleException
{
    public function __construct(
        protected string $productName,
        protected int $available,
        protected int $requested,
    ) {
        parent::__construct(
            "Stok produk '{$productName}' tidak mencukupi. Tersedia: {$available}, diminta: {$requested}."
        );
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getAvailable(): int
    {
        return $this->available;
    }

    public function getRequested(): int
    {
        return $this->requested;
    }

    public function errorCode(): string
    {
        return 'insufficient_stock';
    }

    /**
     * @return array<string, mixed>
     */
    public function details(): array
    {
        return [
            'product' => $this->productName,
            'available' => $this->available,
            'requested' => $this->requested,
        ];
    }
}
