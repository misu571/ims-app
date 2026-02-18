<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\{FileUpload, Select, Textarea, TextInput};
use Filament\Schemas\Components\{Grid, Section};
use Filament\Schemas\Schema;
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
            ->prefix('৳')
            ->required();
        $reorderField = TextInput::make('reorder')
            ->label('Re-order lavel')
            ->integer()
            ->step(1)
            ->required();
        $brandField = Select::make('brand_id')
            ->relationship('brand', 'name')
            ->searchable()
            ->preload()
            ->required();
        $categoryField = Select::make('category_id')
            ->relationship('category', 'name')
            ->searchable()
            ->preload()
            ->required();
        $product_typeField = Select::make('product_type_id')
            ->relationship('productType', 'name')
            ->searchable()
            ->preload()
            ->required();
        $unitField = Select::make('unit_id')
            ->relationship('unit')
            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->symbol})")
            ->searchable(['name'])
            ->preload()
            ->required();
        $supplierField = Select::make('supplier_id')
            ->relationship('supplier', 'name')
            ->searchable()
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
                                    $brandField,
                                    $categoryField,
                                    $product_typeField,
                                    $unitField,
                                ]),
                            Grid::make(12)
                                ->schema([
                                    $costField->columnSpan(3),
                                    $reorderField->columnSpan(2),
                                    $supplierField->columnSpan(7),
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
