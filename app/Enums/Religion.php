<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Religion: string implements HasLabel
{
    case Islam = 'Islam';
    case Hinduism = 'Hinduism';
    case Christianity = 'Christianity';
    case Buddhism = 'Buddhism';
    case Judaism = 'Judaism';
    case Sikhism = 'Sikhism';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
