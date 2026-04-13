<?php

namespace App\Enums;

enum OrderStatus: string
{

  case PENDING = 'pending';

  case IN_PROGRESS = 'in_progress';

  case READY = 'ready';

  case SERVED = 'served';

  case COMPLETED = 'completed';

  case CANCELLED = 'cancelled';

  public static function values(): array
  {
    return array_map(fn($case) => $case->value, self::cases());
  }
}