<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasLabel
{
    case PurchasePending = 'purchase_pending';
    case PurchaseApproved = 'purchase_approved';
    case Rejected = 'rejected';
    case Sold = 'sold';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PurchasePending => 'Pending',
            self::PurchaseApproved => 'Approved',
            self::Rejected => 'Rejected',
            self::Sold => 'Sold',
        };
    }
}
