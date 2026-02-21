<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        $codeField = TextInput::make('code')
            ->unique()
            ->required();
        $nameField = TextInput::make('name')
            ->unique()
            ->maxLength(255)
            ->required();
        $costField = TextInput::make('cost')
            ->numeric()
            ->prefixIcon(Heroicon::CurrencyBangladeshi)
            ->required();
        $reorderField = TextInput::make('reorder')
            ->label('Re-order lavel')
            ->integer()
            ->step(1)
            ->required();
        $categoryField = Select::make('category_id')
            ->relationship('category', 'name')
            ->searchable()
            ->preload()
            ->required();
        $brandField = Select::make('brand_id')
            ->relationship('brand', 'name')
            ->searchable()
            ->preload();
        $product_typeField = Select::make('product_type_id')
            ->relationship('productType', 'name')
            ->searchable()
            ->preload();
        $unitField = Select::make('unit_id')
            ->relationship('unit')
            ->getOptionLabelFromRecordUsing(fn (Model $record): string => empty($record->symbol) ? $record->name : "{$record->name} ({$record->symbol})")
            ->searchable(['name'])
            ->preload()
            ->required();
        $descriptionField = Textarea::make('description')
            ->rows(3);
        $imagesComponent = FileUpload::make('images')
            ->label('Product images')
            ->multiple()
            ->directory('products')
            ->image()
            ->imageEditor();

        $layout = [
            Grid::make(9)
                ->schema([
                    Section::make('Product details')
                        ->schema([
                            Grid::make(8)
                                ->schema([
                                    $codeField->columnSpan(2),
                                    $nameField->columnSpan(6),
                                ]),
                            Grid::make(4)
                                ->schema([
                                    $categoryField,
                                    $brandField,
                                    $product_typeField,
                                    $unitField,
                                ]),
                            Grid::make(6)
                                ->schema([
                                    $costField->columnSpan(4),
                                    $reorderField->columnSpan(2),
                                ]),
                            $descriptionField,
                        ])->columnSpan(6),
                    Section::make([
                        $imagesComponent,
                    ])->columnSpan(3),
                ])->columnSpanFull(),
        ];

        return $schema->dense()->components($layout);
    }
}
