<?php

namespace App\Modules\Core\Roles\Models;

use App\Modules\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Modules\Core\Permissions\Models\Permissions;

class Roles extends BaseModel
{
    protected $fillable = [
        'name',
        'display_name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];


    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'role_permissions');
    }
} 