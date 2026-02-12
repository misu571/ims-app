<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BloodGroup: string implements HasLabel
{
    case Ap = 'a_p';
    case Am = 'a_n';
    case Bp = 'b_p';
    case Bm = 'b_n';
    case Op = 'o_p';
    case Om = 'o_n';
    case ABp = 'ab_p';
    case ABm = 'ab_n';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Ap => "A+",
            self::Am => "A-",
            self::Bp => "B+",
            self::Bm => "B-",
            self::Op => "O+",
            self::Om => "O-",
            self::ABp => "AB+",
            self::ABm => "AB-",
        };
    }
}
