<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Supplier\StoreSupplierRequest;
use App\Http\Requests\v1\Supplier\UpdateSupplierRequest;
use App\Http\Resources\v1\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SupplierController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return SupplierResource::collection(Supplier::all());
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        return response()->json(Supplier::create($request->validated()));
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return response()->json(new SupplierResource($supplier));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $supplier->update($request->validated());
        return response()->json(new SupplierResource($supplier));
    }

    public function destroy(Supplier $supplier): ?bool
    {
       return $supplier->delete();
    }
}
