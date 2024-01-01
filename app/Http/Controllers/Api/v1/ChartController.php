<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\v1\ChartService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ChartController extends Controller
{
    protected ChartService $chartService;

    public function __construct(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function today(): JsonResponse
    {
        $startDate = Carbon::now()->startOfDay();
        $endDate = $startDate->copy()->endOfDay();

        $data = $this->chartService->getDataForPeriod($startDate, $endDate, 'H:00');

        return response()->json($data);
    }

    public function week(): JsonResponse
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $data = $this->chartService->getDataForPeriod($startDate, $endDate, 'd/m');

        return response()->json($data);
    }
}
