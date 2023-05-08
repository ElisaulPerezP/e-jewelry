<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'description' => [
                'required',
                'string',
            ],
            'price' => [
                'required',
                'integer',
            ],
            'stock' => [
                'required',
                'integer',
            ],
            'score' => [
                'required',
                'integer',
            ],
            'barCode' => [
                'required',
                'integer',
            ],
            'image' => [
                'file',
                'mimes:jpeg,bmp,png',
                'max:4000',
            ],

        ];
    }
}
