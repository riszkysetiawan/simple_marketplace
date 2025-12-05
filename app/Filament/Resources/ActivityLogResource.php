<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $modelLabel = 'Activity';

    protected static ?string $pluralModelLabel = 'Activities';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Type')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'product' => 'success',
                        'transaction' => 'info',
                        'category' => 'warning',
                        'user' => 'primary',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Event')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn($state) => $state ? class_basename($state) : '-')
                    ->badge(),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID'),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->options([
                        'product' => 'Product',
                        'transaction' => 'Transaction',
                        'category' => 'Category',
                        'user' => 'User',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Activity Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('log_name')
                            ->label('Type')
                            ->badge(),

                        Infolists\Components\TextEntry::make('description'),

                        Infolists\Components\TextEntry::make('subject_type')
                            ->label('Model')
                            ->formatStateUsing(fn($state) => $state ? class_basename($state) : 'N/A'),

                        Infolists\Components\TextEntry::make('subject_id')
                            ->label('ID'),

                        Infolists\Components\TextEntry::make('causer. name')
                            ->label('User')
                            ->default('System'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Changes')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties. old')
                            ->label('Old Values')
                            ->hidden(fn($record) => empty($record->properties['old'] ?? [])),

                        Infolists\Components\KeyValueEntry::make('properties. attributes')
                            ->label('New Values')
                            ->hidden(fn($record) => empty($record->properties['attributes'] ?? [])),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
