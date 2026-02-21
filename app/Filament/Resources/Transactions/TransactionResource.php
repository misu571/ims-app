<?php

namespace App\Filament\Resources\Transactions;

use App\Enums\Status;
use App\Enums\TransactionType;
use App\Filament\Resources\Transactions\Pages\ManageTransactions;
use App\Models\Inventory;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'trx_id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('items')
                    ->relationship()
                    ->table([
                        TableColumn::make('Product')
                            ->width('70%'),
                        TableColumn::make('Quantity'),
                    ])
                    ->compact()
                    ->schema([
                        Select::make('product_id')
                            ->relationship(
                                name: 'product',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->join('inventories', 'products.id', 'inventories.product_id')
                                    ->where('inventories.stock', '>', 0)
                            )
                            ->getOptionLabelFromRecordUsing(fn (Model $record): string => "[{$record->code}] {$record->name}")
                            ->searchable(['code', 'name'])
                            ->preload()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->live()
                            ->required()
                            ->columnSpan(5),
                        TextInput::make('quantity')
                            ->placeholder(fn (Get $get): string => 'Stock: ' . (empty($get('product_id')) ? '--' : Inventory::where('product_id', $get('product_id'))->first()->stock))
                            ->integer()
                            ->step(1)
                            ->minValue(1)
                            ->maxValue(fn (Get $get): int => empty($get('product_id')) ? 1 : Inventory::where('product_id', $get('product_id'))->first()->stock)
                            ->required()
                            ->columnSpan(2),
                    ])
                    ->addActionLabel('Add new item')
                    ->addActionAlignment(Alignment::End)
                    ->reorderable(false)
                    ->columns(7)
                    ->columnSpanFull()
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        TextEntry::make('trx_id'),
                        TextEntry::make('trx_date')
                            ->date(),
                        TextEntry::make('trx_type')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('validate_by')
                            ->numeric(),
                        TextEntry::make('validate_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('entry_by')
                            ->numeric(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])->columnSpanFull(),
                RepeatableEntry::make('items')
                    ->table([
                        TableColumn::make('Product')
                            ->width('70%'),
                        TableColumn::make('Quantity')
                            ->alignment(Alignment::End),
                    ])
                    ->schema([
                        TextEntry::make('product.name'),
                        TextEntry::make('quantity')
                            ->alignment(Alignment::End),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->recordTitleAttribute('trx_id')
            ->columns([
                TextColumn::make('index')
                    ->label('SL#')
                    ->width('1%')
                    ->rowIndex()
                    ->fontFamily(FontFamily::Mono)
                    ->alignment(Alignment::End),
                TextColumn::make('trx_id')
                    ->searchable(),
                TextColumn::make('trx_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_item')
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label('Total value')
                    ->sortable(),
                TextColumn::make('trx_type')
                    ->label('Type')
                    ->size(TextSize::Large)
                    ->badge()
                    ->colors([
                        'warning' => TransactionType::Receipt,
                        'success' => TransactionType::Sale,
                    ])
                    ->searchable(),
                TextColumn::make('status')
                    ->size(TextSize::Large)
                    ->badge()
                    ->colors([
                        'gray' => Status::PurchasePending,
                        'success' => Status::PurchaseApproved,
                        'danger' => Status::Rejected,
                        'success' => Status::Sold,
                    ])
                    ->searchable(),
                TextColumn::make('validateBy.name')
                    ->label('Validate by')
                    ->placeholder('--')
                    ->searchable(),
                TextColumn::make('validate_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('entryBy.name')
                    ->label('Entry by')
                    ->searchable(),
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
            ->defaultSort('updated_at', 'desc')
            ->recordUrl(null)
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('View transaction')
                    ->modalWidth(Width::ThreeExtraLarge),
                // EditAction::make()
                //     ->hidden(fn (Model $record): bool => $record->trx_type == TransactionType::Sale)
                //     ->modalHeading('Edit transaction')
                //     ->modalWidth(Width::ThreeExtraLarge)
                //     ->databaseTransaction()
                //     ->after(function (Model $record) {
                //         $totalItem = array_sum(array_column($record->items->toArray(), 'quantity'));
                //         $totalPrice = array_sum(array_map(fn ($item) => bcmul($item['quantity'], Product::find($item['product_id'])->cost, 2), $record->items->toArray()));
                        
                //         $record->update(['total_item' => $totalItem, 'total_price' => $totalPrice]);
                //     }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTransactions::route('/'),
        ];
    }
}
