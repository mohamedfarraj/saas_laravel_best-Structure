<?php

namespace App\Modules\Core\Permissions\Models;

use App\Modules\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Modules\Core\Roles\Models\Roles;
class Permissions extends BaseModel
{
    protected $fillable = [
        'name',
        'display_name',
        'group',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
} 