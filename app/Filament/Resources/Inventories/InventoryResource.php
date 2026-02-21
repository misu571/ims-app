<?php

namespace App\Filament\Resources\Inventories;

use App\Enums\Status;
use App\Enums\TransactionType;
use App\Filament\Resources\Inventories\Pages\ManageInventories;
use App\Models\Inventory;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $transactionModel = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquaresPlus;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('stock_value')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product.name')
                    ->label('Product'),
                TextEntry::make('stock')
                    ->numeric(),
                TextEntry::make('stock_value')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('product.code')
                    ->label('Code')
                    ->width('1%')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_value')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product.reorder')
                    ->label('Re-order lavel')
                    ->numeric()
                    ->sortable(),
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
            ->defaultSort('can_reorder', 'desc')
            ->recordAction(null)
            ->recordUrl(null)
            ->recordActions([
                Action::make('order')
                    ->visible(fn (Model $record): bool => $record->can_reorder)
                    ->color('warning')
                    ->tableIcon(Heroicon::OutlinedShoppingBag)
                    ->modalHeading(fn (Model $record): string => "Place order for {$record->product->name}")
                    ->modalWidth(Width::Medium)
                    ->databaseTransaction()
                    ->schema([
                        TextInput::make('quantity')
                            ->integer()
                            ->step(1)
                            ->minValue(1)
                            ->required()
                    ])
                    ->action(function (Model $record, array $data) {
                        $totalStock = bcadd($record->stock, $data['quantity']);
                        $totalStockValue = bcmul($record->product->cost, $totalStock, 2);
                        $reorder = $totalStock <= $record->product->reorder;

                        $record->update(['stock' => $totalStock, 'stock_value' => $totalStockValue, 'can_reorder' => $reorder]);
                        self::$transactionModel::create([
                            'trx_id' => uniqid(),
                            'trx_date' => now(),
                            'total_item' => $data['quantity'],
                            'total_price' => bcmul($record->product->cost, $data['quantity'], 2),
                            'trx_type' => TransactionType::Receipt,
                            'status' => Status::PurchasePending,
                            'entry_by' => auth('web')->id(),
                        ]);

                        // Mail::to($this->client)
                        //     ->send(new GenericEmail(
                        //         subject: $data['subject'],
                        //         body: $data['body'],
                        //     ));
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInventories::route('/'),
        ];
    }
}
