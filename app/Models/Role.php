<?php

namespace App\Models;

use App\Helpers\SlugHelper;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $connection = 'mysql'; // Always use master DB
    protected $fillable = ['name', 'slug', 'guard_name'];

    // generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = SlugHelper::createSlug(new static, $role->name);
            }
        });

        static::updating(function ($role) {
            if ($role->isDirty('name')) { // regenerate slug only if name changes
                $role->slug = SlugHelper::createSlug(new static, $role->name);
            }
        });
    }

    // using slug instead of id
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
