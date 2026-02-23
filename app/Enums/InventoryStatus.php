<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InventoryStatus: string implements HasLabel
{
    case Available = 'Available';
    case Low = 'Low';
    case Out = 'Out';

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Available => 'success',
            self::Low => 'warning',
            self::Out => 'danger',
        };
    }
}
