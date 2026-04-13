<?php

namespace App\Enums;

enum TransactionMethod: string
{
     case CASH = 'cash';
     case CARD = 'card';

     case ONLINE_BANKING = 'online_banking';


     public static function values(): array
     {
          return array_map(fn($case) => $case->value, self::cases());
     }
}