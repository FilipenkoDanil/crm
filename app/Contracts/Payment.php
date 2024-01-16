<?php

namespace App\Contracts;

use App\Http\Requests\v1\Sale\StoreSaleRequest;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface Payment
{
    public function merchant(Sale $sale, StoreSaleRequest $request = null): JsonResponse;
    public function service(Request $request);
}
