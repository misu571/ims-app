<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\{Status, TransactionType};
use App\Filament\Resources\Transactions\TransactionResource;
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
                    $data['trx_id'] = str()->random(8);
                    $data['trx_date'] = now();
                    $data['trx_type'] = TransactionType::Sale;
                    $data['total_item'] = 0;
                    $data['total_price'] = 0;
                    $data['status'] = Status::Sold;
                    $data['entry_by'] = auth('web')->id();

                    return $data;
                // })
                // ->after(function (Model $record) {
                //     dd($record);
                }),
        ];
    }
}
