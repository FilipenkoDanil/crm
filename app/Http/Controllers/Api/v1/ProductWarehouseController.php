<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ProductWarehouse\UpdateProductWarehouseRequest;
use App\Models\ProductWarehouse;
use Illuminate\Http\JsonResponse;

class ProductWarehouseController extends Controller
{
    public function update(UpdateProductWarehouseRequest $request): JsonResponse
    {
        $data = $request->validated();
        $productWarehouse = ProductWarehouse::where('warehouse_id', $request->warehouse_id)->where('product_id', $request->product_id)->firstOrFail();

        $productWarehouse->update([
            'stock' => $data['stock'], //TODO: сделать новый вид движения Adjustment
            'min_stock_notify' => $data['min_stock_notify']
        ]);

        return response()->json($productWarehouse);
    }
}
