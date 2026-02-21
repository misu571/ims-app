<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TransactionType: string implements HasLabel
{
    case Sale = 'sale';
    case Receipt = 'receipt';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Sale => 'Sale',
            self::Receipt => 'Receipt',
        };
    }
}
