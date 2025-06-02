<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\Traits\HasProfilePhoto;

class User extends Authenticatable
{
    /*use HasFactory, Notifiable, HasApiTokens, TwoFactorAuthenticatable, HasProfilePhoto;*/

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'type',
    ];

    protected $attributes = [
        'type' => 'user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function isUser(): bool
    {
        return $this->type === 'user';
    }

    public function isManager(): bool
    {
        return $this->type === 'manager';
    }

    public function application()
    {
        return $this->hasOne(Application::class);
    }

    public function courseApplications()
    {
        return $this->hasMany(CourseApplication::class);
    }
}