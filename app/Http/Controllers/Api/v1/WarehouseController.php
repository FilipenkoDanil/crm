<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\v1\Warehouse\UpdateWarehouseRequest;
use App\Http\Resources\v1\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(WarehouseResource::collection(Warehouse::all()));
    }

    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        return response()->json(Warehouse::create($request->validated()));
    }

    public function show(Warehouse $warehouse): JsonResponse
    {
        return response()->json(new WarehouseResource($warehouse->load('products')));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): JsonResponse
    {
        $warehouse->update($request->validated());
        return response()->json(new WarehouseResource($warehouse));
    }

    public function destroy(Warehouse $warehouse): JsonResponse
    {
        $warehouse->delete();
        return response()->json(['message' => 'Warehouse deleted.']);
    }
}
