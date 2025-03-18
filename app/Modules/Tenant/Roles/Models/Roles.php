<?php

namespace App\Modules\Tenant\Roles\Models;

use App\Modules\Base\Models\BaseModel;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Roles extends BaseModel
{
    use UsesTenantConnection;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function permissions()
    {
        return $this->belongsToMany(
            \App\Modules\Tenant\Permissions\Models\Permissions::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            \App\Modules\Tenant\Users\Models\Users::class,
            'user_roles',
            'role_id',
            'user_id'
        );
    }
} 