<?php

namespace App\Http\Controllers\Products;

use App\Actions\Products\ChangeProductStatusAction;
use App\Actions\Products\CreateProductAction;
use App\Actions\Products\DeleteProductAction;
use App\Actions\Products\GetPaginatedProductsAction;
use App\Actions\Products\UpdateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiProductController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return (new GetPaginatedProductsAction())($request);
    }

    public function update(ProductRequest $request, Product $product): ProductResource
    {
        return (new UpdateProductAction())($request, $product);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function store(ProductRequest $request): ProductResource
    {
        return (new CreateProductAction())($request);
    }

    public function destroy(Product $product): JsonResponse
    {
        (new DeleteProductAction())($product);

        return new JsonResponse(['message' => 'deleted'], 204);
    }

    public function changeStatus(Product $product): ProductResource
    {
        (new ChangeProductStatusAction())($product);

        return new ProductResource($product);
    }
}
