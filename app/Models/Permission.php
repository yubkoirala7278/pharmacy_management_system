<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $connection = 'mysql'; // Always use master DB
    protected $fillable = ['name', 'guard_name'];
}
