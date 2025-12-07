<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Calculate statistics
        $totalRevenue = Transaction::whereIn('status', ['paid', 'completed'])->sum('total_amount');
        $todayRevenue = Transaction::whereIn('status', ['paid', 'completed'])
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $totalOrders = Transaction::count();
        $pendingOrders = Transaction::where('status', 'pending')->count();

        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $outOfStockProducts = Product::where('stock', 0)->count();

        $totalCustomers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->count();

        $newCustomersThisMonth = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->whereMonth('created_at', now()->month)->count();

        return [
            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Today: Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total Orders', number_format($totalOrders))
                ->description($pendingOrders . ' pending orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning')
                ->url(route('filament.admin.resources.transactions.index')),

            Stat::make('Products', number_format($totalProducts))
                ->description($lowStockProducts . ' low stock, ' . $outOfStockProducts . ' out of stock')
                ->descriptionIcon('heroicon-m-cube')
                ->color($lowStockProducts > 0 ? 'danger' : 'success')
                ->url(route('filament.admin.resources.products.index')),

            Stat::make('Customers', number_format($totalCustomers))
                ->description($newCustomersThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
