<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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

            'searching' => [
                'string',
                'nullable',
                ],
            'per_page' => [
                'required',
                'integer',
            ],
            'current_page' => [
                'required',
                'integer',
            ],
            'flag' => [
                'required',
                'string',
            ],

        ];
    }
}
