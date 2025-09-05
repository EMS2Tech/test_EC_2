<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'title', 'full_name', 'name_with_initials', 'birthday', 'nationality',
        'nic_number', 'other_nationality', 'passport_number', 'address', 'contact_number',
        'whatsapp_number', 'email_address', 'nic_photo', 'passport_photo', 'photograph',
        'application_completed', 'status', 'rejection_reason'
    ];

    protected $casts = [
        'application_completed' => 'boolean',
        'birthday' => 'date',
    ];
}