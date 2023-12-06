<?php

namespace App\Services\v1;

use App\Http\Requests\v1\Sale\StoreSaleRequest;
use App\Http\Resources\v1\SaleResource;
use App\Models\Sale;
use App\Models\Traits\HandlesMovements;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SaleService
{
    use HandlesMovements;

    public function store(StoreSaleRequest $request): JsonResponse
    {
        if (!$request->has('data')) {
            return response()->json(['message' => 'Products do not exist.'], 422);
        }

        $data = $request->validated();

        $sale = DB::transaction(function () use ($request, $data) {
            $sale = $this->createSale($data);
            $this->createMovements($sale, $request->input('data'), $data['warehouse_id'], 'selling_price');
            $this->updateTotalAmount($sale);
            $this->calculateProfit($sale);
            $this->updateStock($sale->movements, 'subtract');
            return $sale;
        });

        return response()->json(new SaleResource($sale));
    }

    protected function createSale(array $data)
    {
        return Sale::create([
            'client_id' => $data['client_id'],
            'user_id' => 1 // TODO после добавления авторизации заменить на Auth::id()
        ]);
    }

    protected function calculateProfit(Sale $sale): void
    {
        $sale->profit = $sale->movements->map(function ($item) {
            return ($item->unit_price - $item->product->purchase_price) * $item->quantity;
        })->sum();
        $sale->save();
    }
}
