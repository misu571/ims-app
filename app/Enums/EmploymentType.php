<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EmploymentType: string implements HasLabel
{
    case Fulltime = 'full_time';
    case Parttime = 'part_time';
    case Intern = 'intern';
    case Temporary = 'temporary';
    case Contractual = 'contractual';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Fulltime => 'Full-time',
            self::Parttime => 'Part-time',
            self::Intern => 'Intern',
            self::Temporary => 'Temporary',
            self::Contractual => 'Contractual',
        };
    }
}
