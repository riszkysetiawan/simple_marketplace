<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Overview';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';

    public ?string $filter = '7days';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = match ($activeFilter) {
            '7days' => Trend::model(Transaction::class)
                ->between(
                    start: now()->subDays(7),
                    end: now(),
                ),
            '30days' => Trend::model(Transaction::class)
                ->between(
                    start: now()->subDays(30),
                    end: now(),
                ),
            '90days' => Trend::model(Transaction::class)
                ->between(
                    start: now()->subDays(90),
                    end: now(),
                ),
            'year' => Trend::model(Transaction::class)
                ->between(
                    start: now()->subYear(),
                    end: now(),
                ),
            default => Trend::model(Transaction::class)
                ->between(
                    start: now()->subDays(7),
                    end: now(),
                ),
        };

        $data = $query
            ->perDay()
            ->sum('total_amount');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (Rp)',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7days' => 'Last 7 days',
            '30days' => 'Last 30 days',
            '90days' => 'Last 90 days',
            'year' => 'This year',
        ];
    }
}
