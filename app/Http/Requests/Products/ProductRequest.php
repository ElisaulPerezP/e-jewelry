<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'category' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'score' => [
                'required',
                'integer',
                'exists:scores,id',
            ],
            'barCode' => [
                'required',
                'integer',
                Rule::unique('products'),
            ],
            'product_image' => [
                'file',
                'mimes:jpeg,bmp,png',
                'size:4000',
            ],

        ];
    }
}
