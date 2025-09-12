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
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'type',
    ];

    protected $attributes = [
        'type' => 'student', // Changed default to 'student' for new users, adjust as needed
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

    // Define all possible user types
    public static $userTypes = ['student', 'admin', 'manager', 'finance_manager', 'course_manager', 'front_manager'];

    // Check methods for all user types
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->type === 'student';
    }

    public function isManager(): bool
    {
        return $this->type === 'manager';
    }

    public function isFinanceManager(): bool
    {
        return $this->type === 'finance_manager';
    }

    public function isCourseManager(): bool
    {
        return $this->type === 'course_manager';
    }

    public function isFrontManager(): bool
    {
        return $this->type === 'front_manager';
    }

    // Relationships
    public function application()
    {
        return $this->hasOne(Application::class);
    }

    public function courseApplications()
    {
        return $this->hasMany(CourseApplication::class);
    }
}