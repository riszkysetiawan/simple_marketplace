<?php

namespace App\Filament\Customer\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;

class CustomerOrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        $totalOrders = Transaction::where('user_id', $userId)->count();
        $pendingOrders = Transaction::where('user_id', $userId)
            ->whereIn('status', ['pending', 'processing'])
            ->count();
        $completedOrders = Transaction::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        $totalSpent = Transaction::where('user_id', $userId)
            ->whereIn('status', ['completed', 'shipped'])
            ->sum('total_amount');

        return [
            Stat::make('Total Orders', $totalOrders)
                ->description('All your orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Pending', $pendingOrders)
                ->description('Orders in progress')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Completed', $completedOrders)
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Spent', 'Rp ' . number_format($totalSpent, 0, ',', '.'))
                ->description('Lifetime spending')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}
