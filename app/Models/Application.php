<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'full_name',
        'name_with_initials',
        'birthday',
        'nationality',
        'nic_number',
        'nic_photo',
        'other_nationality',
        'passport_photo',
        'photograph',
        'address',
        'contact_number',
        'whatsapp_number',
        'email_address',
        'application_completed',
    ];

    protected $casts = [
        'birthday' => 'date',
        'application_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}