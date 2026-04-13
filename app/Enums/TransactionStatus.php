<?php

namespace App\Enums;

enum TransactionStatus: string
{

  case COMPLETED = 'completed';

  case REFUND = 'refund';

  public static function values(): array
  {
    return array_map(fn($case) => $case->value, self::cases());
  }
}