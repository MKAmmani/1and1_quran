<?php

namespace App\Enums;

enum UserRole: string
{
    case Teacher = 'teacher';
    case Student = 'student';
    public static function values(): array
    {
        return array_column(UserRole::cases(), 'value');
    }
}
