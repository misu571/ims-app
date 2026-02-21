<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\{Status, TransactionType};
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Item;
use App\Models\Product;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New sale')
                ->color('success')
                ->icon(Heroicon::OutlinedShoppingCart)
                ->modalHeading('Create sale')
                ->modalWidth(Width::ThreeExtraLarge)
                ->databaseTransaction()
                ->mutateDataUsing(function (array $data): array {
                    $data['trx_id'] = uniqid();
                    $data['trx_date'] = now();
                    $data['trx_type'] = TransactionType::Sale;
                    $data['status'] = Status::Sold;
                    $data['entry_by'] = auth('web')->id();

                    return $data;
                })
                ->after(function (Model $record) {
                    $totalItem = array_sum(array_column($record->items->toArray(), 'quantity'));
                    $totalPrice = array_sum(array_map(fn ($item) => bcmul($item['quantity'], Product::find($item['product_id'])->cost, 2), $record->items->toArray()));
                    
                    $record->update(['total_item' => $totalItem, 'total_price' => $totalPrice]);
                    array_map(
                        function ($item) {
                            $itemCollection = Item::find($item['id']);
                            $totalStock = bcsub($itemCollection->product->inventory->stock, $item['quantity']);
                            $totalStockValue = bcmul($itemCollection->product->cost, $totalStock, 2);
                            $reorder = $totalStock <= $itemCollection->product->reorder;

                            $itemCollection->product->inventory->update(['stock' => $totalStock, 'stock_value' => $totalStockValue, 'can_reorder' => $reorder]);
                        },
                        $record->items->toArray()
                    );
                }),
        ];
    }
}
