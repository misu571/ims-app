<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Inventory;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected ?bool $hasDatabaseTransactions = true;
    
    protected static ?string $title = 'Create record';

    protected function afterCreate(): void
    {
        Inventory::create([
            'product_id' => $this->getRecord()->id,
            'stock' => 0,
            'stock_value' => 0,
        ]);
    }
}
