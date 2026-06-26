<?php

namespace App\Models;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SALES = 'sales';
    case TAILOR = 'tailor';
    case PRODUCTION = 'production';
    case MANAGER = 'manager';
}
