<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Sale\StoreSaleRequest;
use App\Http\Resources\v1\SaleResource;
use App\Models\Sale;
use App\Services\v1\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SaleController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(): AnonymousResourceCollection
    {
        return SaleResource::collection(Sale::orderBy('created_at', 'desc')->with('client', 'user', 'payment')->get());
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        return $this->saleService->store($request);
    }

    public function show(Sale $sale): JsonResponse
    {
        $sale = Sale::with('movements.product', 'movements.warehouse', 'client', 'user')->findOrFail($sale->id);
        return response()->json(new SaleResource($sale));
    }
}
