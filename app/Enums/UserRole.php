<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case ACCOUNTANT = 'accountant';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::USER => 'Usuario',
            self::ACCOUNTANT => 'Contador',
        };
    }
}
