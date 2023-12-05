<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Purchase\StorePurchaseRequest;
use App\Http\Resources\v1\PurchaseResource;
use App\Models\Purchase;
use App\Services\v1\PurchaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    protected PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(): AnonymousResourceCollection
    {
        return PurchaseResource::collection(Purchase::orderBy('created_at', 'desc')->paginate(15));
    }

    public function store(StorePurchaseRequest $request): JsonResponse
    {
        return $this->purchaseService->store($request);
    }

    public function show(Purchase $purchase): JsonResponse
    {
        return response()->json(new PurchaseResource($purchase->load('movements')));
    }

    public function update(Purchase $purchase): JsonResponse
    {
        DB::transaction(function () use ($purchase) {
            $this->purchaseService->updateStock($purchase->movements, $purchase->isApproved ? 'subtract' : 'add');
            $purchase->isApproved = !$purchase->isApproved;
            $purchase->save();
        });
        return response()->json(new PurchaseResource($purchase));
    }
}
