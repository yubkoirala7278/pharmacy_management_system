<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\SlugHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web'; // master guard

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'name',
        'email',
        'password',
    ];

    // generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = SlugHelper::createSlug(new static, $user->name);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('name')) { // regenerate slug only if name changes
                $user->slug = SlugHelper::createSlug(new static, $user->name);
            }
        });
    }

    // using slug instead of id
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
