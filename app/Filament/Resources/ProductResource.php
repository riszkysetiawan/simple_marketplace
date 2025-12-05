<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Exports\ProductsExport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Information')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated(),
                            ]),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn(string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Product::class, 'slug', ignoreRecord: true)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(255)
                            ->unique(Product::class, 'sku', ignoreRecord: true)
                            ->default(fn() => 'PRD-' . strtoupper(Str::random(8))),

                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->step(1000)
                            ->minValue(0),

                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('products')
                            ->imageEditor()
                            ->label('Main Image'),

                        Forms\Components\FileUpload::make('images')
                            ->multiple()
                            ->image()
                            ->directory('products')
                            ->imageEditor()
                            ->label('Additional Images')
                            ->maxFiles(5),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn(string $state): string => match (true) {
                        $state == 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),

                Tables\Filters\Filter::make('out_of_stock')
                    ->query(fn($query) => $query->where('stock', '<=', 0))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // ✅ Export Selected to Excel
                    Tables\Actions\BulkAction::make('exportExcel')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            return Excel::download(
                                new ProductsExport(['ids' => $records->pluck('id')->toArray()]),
                                'products_' . now()->format('Y-m-d_His') . '.xlsx'
                            );
                        })
                        ->deselectRecordsAfterCompletion()
                        ->color('success'),

                    // ✅ Export Selected to PDF
                    Tables\Actions\BulkAction::make('exportPdf')
                        ->label('Export to PDF')
                        ->icon('heroicon-o-document-text')
                        ->action(function ($records) {
                            $products = $records;
                            $pdf = Pdf::loadView('exports.products-pdf', compact('products'));
                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'products_' . now()->format('Y-m-d_His') . '.pdf');
                        })
                        ->deselectRecordsAfterCompletion()
                        ->color('danger'),

                    // ✅ Bulk Activate
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->color('success'),

                    // ✅ Bulk Deactivate
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->color('warning'),
                ]),
            ])
            ->headerActions([
                // ✅ Export ALL to Excel
                Tables\Actions\Action::make('exportAllExcel')
                    ->label('Export All to Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return Excel::download(
                            new ProductsExport(),
                            'all_products_' . now()->format('Y-m-d_His') . '.xlsx'
                        );
                    })
                    ->color('success'),

                // ✅ Export ALL to PDF
                Tables\Actions\Action::make('exportAllPdf')
                    ->label('Export All to PDF')
                    ->icon('heroicon-o-document-text')
                    ->action(function () {
                        $products = Product::with('category')->get();
                        $pdf = Pdf::loadView('exports.products-pdf', compact('products'));
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'all_products_' . now()->format('Y-m-d_His') . '.pdf');
                    })
                    ->color('danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
