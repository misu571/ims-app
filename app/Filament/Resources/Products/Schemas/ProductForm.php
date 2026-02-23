<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\Gender;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
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
            ->createOptionForm([
                TextInput::make('name')
                    ->unique()
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->relationship('parentCategory', 'name', fn (Builder $query, ?Model $record): ?Builder => $query->whereKeyNot($record?->id))
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
            ])
            ->createOptionAction(
                fn (Action $action) => $action->modalWidth(Width::Medium),
            )
            ->searchable()
            ->preload()
            ->required();
        $brandField = Select::make('brand_id')
            ->relationship('brand', 'name')
            ->createOptionForm([
                TextInput::make('name')
                    ->unique()
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
            ])
            ->createOptionAction(
                fn (Action $action) => $action->modalWidth(Width::Medium),
            )
            ->searchable()
            ->preload();
        $product_typeField = Select::make('product_type_id')
            ->relationship('productType', 'name')
            ->createOptionForm([
                TextInput::make('name')
                    ->unique()
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
            ])
            ->createOptionAction(
                fn (Action $action) => $action->modalWidth(Width::Medium),
            )
            ->searchable()
            ->preload();
        $unitField = Select::make('unit_id')
            ->relationship('unit')
            ->getOptionLabelFromRecordUsing(fn (Model $record): string => empty($record->symbol) ? $record->name : "{$record->name} ({$record->symbol})")
            ->createOptionForm([
                TextInput::make('name')
                    ->unique()
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('symbol')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ])
            ->createOptionAction(
                fn (Action $action) => $action->modalWidth(Width::Medium),
            )
            ->searchable(['name'])
            ->preload()
            ->required();
        $supplierField = Select::make('supplier_id')
            ->relationship('supplier', 'name')
            ->createOptionForm([
                Section::make([
                    FileUpload::make('image')
                        ->hiddenLabel()
                        ->image()
                        ->avatar()
                        ->imageEditor()
                        ->imageEditorAspectRatioOptions(['1:1'])
                        ->alignCenter()
                        ->columnSpanFull(),
                    Grid::make(3)
                        ->schema([
                            TextInput::make('name')
                                ->maxLength(255)
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('phone')
                                ->tel()
                                ->required()
                                ->columnSpan(1),
                            TextInput::make('email')
                                ->email()
                                ->unique()
                                ->columnSpan(2),
                            Select::make('gender')
                                ->options(Gender::class)
                                ->columnSpan(1),
                        ]),
                ])->columnSpanFull(),
            ])
            ->createOptionAction(
                fn (Action $action) => $action->modalWidth(Width::ThreeExtraLarge),
            )
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
            Grid::make(10)
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
                            Grid::make(12)
                                ->schema([
                                    $costField->columnSpan(3),
                                    $reorderField->columnSpan(2),
                                    $supplierField->columnSpan(7),
                                ]),
                            $descriptionField,
                        ])->columnSpan(7),
                    Section::make([
                        $imagesComponent,
                    ])->columnSpan(3),
                ])->columnSpanFull(),
        ];

        return $schema->dense()->components($layout);
    }
}
