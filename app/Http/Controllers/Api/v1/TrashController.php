<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\DeletedClientResource;
use App\Http\Resources\v1\DeletedProductResource;
use App\Http\Resources\v1\DeletedSupplierResource;
use App\Http\Resources\v1\DeletedWarehouseResource;
use App\Models\Client;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TrashController extends Controller
{
    public function products(): AnonymousResourceCollection
    {
        return DeletedProductResource::collection(Product::onlyTrashed()->orderBy('deleted_at', 'desc')->get());
    }

    public function suppliers(): AnonymousResourceCollection
    {
        return DeletedSupplierResource::collection(Supplier::onlyTrashed()->orderBy('deleted_at', 'desc')->get());
    }

    public function clients(): AnonymousResourceCollection
    {
        return DeletedClientResource::collection(Client::onlyTrashed()->orderBy('deleted_at', 'desc')->get());
    }

    public function warehouses(): AnonymousResourceCollection
    {
        return DeletedWarehouseResource::collection(Warehouse::onlyTrashed()->orderBy('deleted_at', 'desc')->get());
    }

    public function restoreProduct($product): JsonResponse
    {
        return $this->restoreItem(Product::class, $product);
    }

    protected function restoreSupplier($supplier): JsonResponse
    {
        return $this->restoreItem(Supplier::class, $supplier);
    }

    protected function restoreClient($client): JsonResponse
    {
        return $this->restoreItem(Client::class, $client);
    }

    protected function restoreWarehouse($warehouse): JsonResponse
    {
        return $this->restoreItem(Warehouse::class, $warehouse);
    }

    public function restoreItem($model, $itemId): JsonResponse
    {
        $item = $model::withTrashed()->findOrFail($itemId);

        return $item->restoreItem();
    }
}
