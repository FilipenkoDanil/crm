<?php

namespace App\Services\v1;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ChartService
{
    public function getDataForPeriod(Carbon $startDate, Carbon $endDate, string $groupByFormat): array
    {
        $sales = Sale::where('isPaid', true)->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();

        $labels = collect(Carbon::parse($startDate)->range($endDate, '1 ' . $this->getRangeUnit($groupByFormat)))->map->format($groupByFormat);

        $groupedSales = $sales->groupBy(function ($sale) use ($groupByFormat) {
            return $sale->created_at->format($groupByFormat);
        });

        return $this->chartData($labels, $groupedSales);
    }

    protected function getRangeUnit(string $groupByFormat): string
    {
        $formats = [
            'H:00' => 'hour',
            'd/m' => 'day',
        ];

        return $formats[$groupByFormat] ?? 'day';
    }

    protected function chartData(Collection $labels, Collection $sales): array
    {
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Amount',
                    'data' => $labels->map(fn($day) => isset($sales[$day]) ? $sales[$day]->sum('total_amount') : 0),
                ],
                [
                    'label' => 'Profit',
                    'data' => $labels->map(fn($day) => isset($sales[$day]) ? $sales[$day]->sum('profit') : 0),
                ],
            ],
        ];
    }
}
