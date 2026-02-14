<?php

namespace App\Filament\Resources\ProductTypes\Pages;

use App\Filament\Resources\ProductTypes\ProductTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageProductTypes extends ManageRecords
{
    protected static string $resource = ProductTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add record')
                ->modalHeading('Create record')
                ->modalWidth(Width::Medium),
        ];
    }
}
