<?php

namespace App\Services\v1;

use App\Http\Requests\v1\Purchase\StorePurchaseRequest;
use App\Http\Resources\v1\PurchaseResource;
use App\Models\Purchase;
use App\Models\Traits\HandlesMovements;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    use HandlesMovements;
    public function store(StorePurchaseRequest $request): JsonResponse
    {
        if (!$request->has('data')) {
            return response()->json(['message' => 'Products do not exist.'], 422);
        }

        $data = $request->validated();

        try {
            $purchase = DB::transaction(function () use ($request, $data) {
                $purchase = $this->createPurchase($data);
                $this->createMovements($purchase, $request->input('data'), $data['warehouse_id'], 'purchase_price');
                $this->updateTotalAmount($purchase);
                return $purchase;
            });
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return response()->json(new PurchaseResource($purchase));
    }

    protected function createPurchase(array $data)
    {
        if (auth()->check()) {
            return Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => auth()->id()
            ]);
        } else {
            throw new \Exception('Unauthorized.', 419);
        }

    }
}
