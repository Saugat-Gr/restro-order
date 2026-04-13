<?php

namespace App\Enums;

enum TableStatus: string
{
     case BOOKED = 'booked';
     case AVAILABLE = 'available';

     case UNAVAILABLE = 'unavailable';


     public static function values(): array
     {
          return array_map(fn($case) => $case->value, self::cases());
     }
}