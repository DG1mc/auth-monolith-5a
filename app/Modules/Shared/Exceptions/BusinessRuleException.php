<?php

namespace Modules\Shared\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Basis semua exception aturan bisnis. Dipakai lintas modul agar format error konsisten.
 */
abstract class BusinessRuleException extends RuntimeException
{
    abstract public function errorCode(): string;

    /**
     * @return array<string, mixed>
     */
    public function details(): array
    {
        return [];
    }

    /**
     * Konteks tambahan yang otomatis ditulis ke storage/logs/laravel.log.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return ['error' => $this->errorCode()] + $this->details();
    }

    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'error' => $this->errorCode(),
                'details' => $this->details(),
            ], 422);
        }

        return back()->withInput()->with('error', $this->getMessage());
    }
}
