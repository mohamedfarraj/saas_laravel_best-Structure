<?php

namespace App\Modules\Tenant\Permissions\Models;

use App\Modules\Base\Models\BaseModel;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Permissions extends BaseModel
{
    use UsesTenantConnection;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            \App\Modules\Tenant\Roles\Models\Roles::class,
            'role_permissions',
            'permission_id',
            'role_id'
        );
    }
} 