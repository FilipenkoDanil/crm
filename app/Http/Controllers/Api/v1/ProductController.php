<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Product\StoreProductRequest;
use App\Http\Requests\v1\Product\UpdateProductRequest;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use App\Services\v1\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::orderBy('created_at', 'desc')->with('category')->get());
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        return $this->productService->store($request);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json(new ProductResource($product->load(['movements' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])));
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        return $this->productService->update($request, $product);
    }

    public function destroy(Product $product): ?bool
    {
        return $product->delete();
    }
}
