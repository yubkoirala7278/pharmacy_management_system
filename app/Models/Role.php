<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $connection = 'mysql'; // Always use master DB
    protected $fillable = ['name', 'guard_name'];
}
