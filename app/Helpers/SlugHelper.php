<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class SlugHelper
{
    public static function createSlug(Model $model, string $value, string $slugField = 'slug'): string
    {
        // base slug
        $slug = Str::slug($value);
        $original = $slug;

        // find unique
        $i = 1;
        while ($model->newQuery()->where($slugField, $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
