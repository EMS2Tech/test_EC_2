<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'title', 'full_name', 'name_with_initials', 'birthday', 'nationality',
        'nic_number', 'other_nationality', 'passport_number', 'address', 'contact_number',
        'whatsapp_number', 'home_number', 'email_address', 'nic_photo', 'passport_photo', 'photograph',
        'application_completed', 'status', 'rejection_reason', 'updated_by' // Added
    ];

    protected $casts = [
        'application_completed' => 'boolean',
        'birthday' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courseApplications()
    {
        return $this->hasMany(CourseApplication::class, 'user_id', 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id', 'user_id');
    }

    // Relationship to the admin who updated the status
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}