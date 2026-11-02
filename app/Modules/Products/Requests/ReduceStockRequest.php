<?php

namespace Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReduceStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'quantity.required' => 'Jumlah pengurangan wajib diisi.',
            'quantity.integer' => 'Jumlah pengurangan harus berupa bilangan bulat.',
            'quantity.min' => 'Jumlah pengurangan minimal 1.',
        ];
    }
}
