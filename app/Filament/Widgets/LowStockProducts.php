<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockProducts extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->with('category')
                    ->where('stock', '<=', 10)
                    ->orderBy('stock', 'asc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('stock')
                    ->badge()
                    ->colors([
                        'danger' => fn($state) => $state == 0,
                        'warning' => fn($state) => $state > 0 && $state <= 5,
                        'success' => fn($state) => $state > 5 && $state <= 10,
                    ])
                    ->icons([
                        'heroicon-o-x-circle' => fn($state) => $state == 0,
                        'heroicon-o-exclamation-triangle' => fn($state) => $state > 0 && $state <= 10,
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn(Product $record): string => route('filament.admin.resources.products.edit', $record))
                    ->icon('heroicon-m-pencil-square'),
            ]);
    }

    protected function getTableHeading(): string
    {
        return '⚠️ Low Stock Products';
    }
}
