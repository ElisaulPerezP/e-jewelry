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
                'integer',
            ],
            'barCode' => [
                'required',
                'integer',
                $this->routeIs('api.products.store') ?
                    Rule::unique('products', 'barCode') :
                    Rule::unique('products', 'barCode')->ignore($this->route('product')->getKey()),
            ],
            'image' => [
                'file',
                'mimes:jpeg,bmp,png',
                'max:4000',
            ],

        ];
    }
}
