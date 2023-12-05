<?php

namespace App\Services\v1;

use App\Http\Requests\v1\Purchase\StorePurchaseRequest;
use App\Http\Resources\v1\PurchaseResource;
use App\Models\ProductWarehouse;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function store(StorePurchaseRequest $request): JsonResponse
    {
        if (!$request->has('data')) {
            return response()->json(['message' => 'Products do not exist.'], 422);
        }

        $data = $request->validated();

        $purchase = DB::transaction(function () use ($request, $data) {
            $purchase = $this->createPurchase($data);
            $this->createMovements($purchase, $request->input('data'), $data['warehouse_id']);
            $this->updateTotalAmount($purchase);
            return $purchase;
        });

        return response()->json(new PurchaseResource($purchase));
    }

    protected function createPurchase(array $data)
    {
        return Purchase::create([
            'supplier_id' => $data['supplier_id'],
            'user_id' => 1 // TODO после добавления авторизации заменить на Auth::id()
        ]);
    }

    protected function createMovements(Purchase $purchase, array $data, $warehouseId): void
    {
        foreach ($data as $item) {
            $purchase->movements()->create([
                'product_id' => $item['id'],
                'warehouse_id' => $warehouseId,
                'quantity' => $item['quantity'],
                'unit_price' => $item['purchase_price']
            ]);
        }
    }

    protected function updateTotalAmount(Purchase $purchase): void
    {
        $purchase->total_amount = $purchase->movements->map(function ($item) {
            return $item->quantity * $item->unit_price;
        })->sum();
        $purchase->save();
    }

    public function updateStock(Collection $movements, $operation = 'add'): void
    {
        $movements->map(function ($item) use ($operation) {
            $productWarehouse = ProductWarehouse::where('product_id', $item->product_id)->where('warehouse_id', $item->warehouse_id)->firstOrCreate([
                'warehouse_id' => $item->warehouse_id,
                'product_id' => $item->product_id
            ]);

            if ($operation === 'add') {
                $productWarehouse->stock += $item->quantity;
            } elseif ($operation === 'subtract') {
                $productWarehouse->stock -= $item->quantity;
            }

            $productWarehouse->save();
        });
    }
}
