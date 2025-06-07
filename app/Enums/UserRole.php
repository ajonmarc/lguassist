<?php

namespace App\Enums;

enum UserRole: string
{
    case Citizen = 'citizen';
    case Admin = 'admin';
    case LGU = 'lgu';

    // public function label(): string
    // {
    //     return match ($this) {
    //         self::Citizen => 'Citizen',
    //         self::Admin => 'Admin',
    //         self::LGU => 'Local Government Unit',
    //     };
    // }
    
}
