<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super-admin';
    case OWNER = 'owner';
    case STAFF = 'staff';
}