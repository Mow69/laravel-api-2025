<?php

namespace App\Enums;

enum StatusType: string
{
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case ACTIVE = 'active';

    public function label(): string
    {
        return match($this) {
            self::INACTIVE => 'Inactif',
            self::PENDING => 'En attente',
            self::ACTIVE => 'Actif',
        };
    }
}
