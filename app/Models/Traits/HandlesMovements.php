<?php

namespace App\Models\Traits;

use App\Models\ProductWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HandlesMovements
{
    public function createMovements(Model $model, array $data, $warehouseId, $unitPriceKey): void
    {
        foreach ($data as $item) {
            $model->movements()->create([
                'product_id' => $item['id'],
                'warehouse_id' => $warehouseId,
                'quantity' => $item['quantity'],
                'unit_price' => $item[$unitPriceKey]
            ]);
        }
    }

    protected function updateTotalAmount(Model $model): void
    {
        $model->total_amount = $model->movements->map(function ($item) {
            return $item->quantity * $item->unit_price;
        })->sum();
        $model->save();
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
