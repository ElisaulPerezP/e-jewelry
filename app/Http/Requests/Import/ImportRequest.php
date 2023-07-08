<?php

namespace App\Http\Requests\Import;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'comments' => [
                'string',
            ],
            'file' => [
                'file',
                'mimes:xlsx',
                'max:1073741824',
            ],
        ];
    }
}
