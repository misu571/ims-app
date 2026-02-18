<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\EditAction;
use Filament\Support\Enums\{Alignment, FontFamily};
use Filament\Tables\Columns\{ImageColumn, TextColumn};
use Filament\Tables\Table;

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
                    ->imageHeight(40)
                    ->square(),
                TextColumn::make('cost')
                    ->fontFamily(FontFamily::Mono)
                    ->sortable(),
                TextColumn::make('reorder')
                    ->label('Re-order lavel')
                    ->fontFamily(FontFamily::Mono)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('productType.name')
                    ->searchable(),
                TextColumn::make('unit.name')
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
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->recordAction(null)
            ->recordUrl(null)
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
