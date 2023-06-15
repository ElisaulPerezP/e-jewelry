<?php

namespace App\Http\Controllers\Products;

use App\Actions\Products\GetPaginatedProductsAction;
use App\Actions\Products\UpdateProductAction;
use App\Actions\Products\CreateProductAction;
use App\Actions\Products\DeleteProductAction;
use App\Actions\Products\ChangeProductStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiProductController extends Controller
{
    protected $getPaginatedProductsAction;
    protected $updateProductAction;
    protected $createProductAction;
    protected $deleteProductAction;
    protected $changeProductStatusAction;

    public function __construct(
        GetPaginatedProductsAction $getPaginatedProductsAction,
        UpdateProductAction $updateProductAction,
        CreateProductAction $createProductAction,
        DeleteProductAction $deleteProductAction,
        ChangeProductStatusAction $changeProductStatusAction
    ) {
        $this->getPaginatedProductsAction = $getPaginatedProductsAction;
        $this->updateProductAction = $updateProductAction;
        $this->createProductAction = $createProductAction;
        $this->deleteProductAction = $deleteProductAction;
        $this->changeProductStatusAction = $changeProductStatusAction;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->getPaginatedProductsAction->execute($request);
    }

    public function update(ProductRequest $request, Product $product): ProductResource
    {
        return $this->updateProductAction->execute($request, $product);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function store(ProductRequest $request): ProductResource
    {
        return $this->createProductAction->execute($request);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->deleteProductAction->execute($product);

        return new JsonResponse(['message' => 'deleted'], 204);
    }

    public function changeStatus(Product $product): ProductResource
    {
        $this->changeProductStatusAction->execute($product);

        return new ProductResource($product);
    }
}
