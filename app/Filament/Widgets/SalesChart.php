<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Sales';
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Transaction::query()
            ->whereIn('status', ['paid', 'completed'])
            ->whereYear('created_at', now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = collect(range(1, 12))->map(function ($month) use ($data) {
            $record = $data->firstWhere('month', $month);
            return [
                'month' => date('M', mktime(0, 0, 0, $month, 1)),
                'count' => $record->count ?? 0,
                'revenue' => $record->revenue ?? 0,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $months->pluck('count'),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                ],
            ],
            'labels' => $months->pluck('month'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
