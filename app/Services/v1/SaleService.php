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

        try {
            $sale = DB::transaction(function () use ($request, $data) {
                $sale = $this->createSale($data);
                $this->createMovements($sale, $request->input('data'), $data['warehouse_id'], 'selling_price');
                $this->updateTotalAmount($sale);
                $this->calculateProfit($sale);
                $this->updateStock($sale->movements, 'subtract');
                return $sale;
            });

            return response()->json(new SaleResource($sale));
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    protected function createSale(array $data)
    {
        if (auth()->check()) {
            return Sale::create([
                'client_id' => $data['client_id'],
                'user_id' => auth()->id()
            ]);
        } else {
            throw new \Exception('Unauthorized.', 419);
        }
    }

    protected function calculateProfit(Sale $sale): void
    {
        $sale->profit = $sale->movements->map(function ($item) {
            return ($item->unit_price - $item->product->purchase_price) * $item->quantity;
        })->sum();
        $sale->save();
    }
}
