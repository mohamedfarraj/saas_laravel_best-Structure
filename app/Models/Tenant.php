<?php

namespace App\Models;

use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    protected $guarded = [];
    
    public static function getCustomColumns(): array
    {
        return [
            'name',
            'domain',
            'database',
        ];
    }
} 