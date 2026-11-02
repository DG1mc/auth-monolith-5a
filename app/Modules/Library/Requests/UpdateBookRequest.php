<?php

namespace Modules\Library\Requests;

use Illuminate\Validation\Rule;

class UpdateBookRequest extends StoreBookRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['isbn'] = [
            'required',
            'string',
            'regex:/^(?:\d{9}[\dX]|97[89]\d{10})$/',
            Rule::unique('books', 'isbn')->ignore($this->route('book')->id),
        ];

        return $rules;
    }
}
