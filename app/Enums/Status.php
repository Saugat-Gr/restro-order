<?php

namespace App\Enums;

enum Status: string
{
     case ACTIVE = 'active';
     case IN_ACTIVE = 'inactive';


     public static function values(): array
     {
          return array_map(fn($case) => $case->value, self::cases());
     }
}