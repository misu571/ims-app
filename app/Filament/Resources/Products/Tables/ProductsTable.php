<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Actions\{DeleteAction, EditAction, RestoreAction};

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('SL#')
                    ->width('1%')
                    ->rowIndex()
                    ->fontFamily(FontFamily::Mono)
                    ->alignment(Alignment::End),
                TextColumn::make('code')
                    ->width('1%')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                ImageColumn::make('thumbnail')
                    ->label('Image')
                    ->imageHeight(40)
                    ->square(),
                TextColumn::make('cost')
                    ->fontFamily(FontFamily::Mono)
                    ->sortable(),
                TextColumn::make('unit.name')
                    ->searchable(),
                TextColumn::make('reorder')
                    ->label('Re-order lavel')
                    ->fontFamily(FontFamily::Mono)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->placeholder('--')
                    ->searchable(),
                TextColumn::make('productType.name')
                    ->label('Type')
                    ->placeholder('--')
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->searchable(),
                TextColumn::make('entryBy.name')
                    ->label('Entry by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordAction(null)
            ->recordUrl(null)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
