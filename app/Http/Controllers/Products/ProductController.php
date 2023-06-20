<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('products.index');
    }
    public function show(int $productId): View
    {
        return view('products.show', compact('productId'));
    }
    public function edit(int $productId): View
    {
        return view('products.edit', compact('productId'));
    }
    public function create(): View
    {
        return view('products.create');
    }
}
