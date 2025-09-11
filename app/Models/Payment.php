<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'application_id',
        'status',
        'payment_slip',
        'payment_type',
        'remark',
        'rejection_reason',
        'updated_by', // Added to track the admin who updated the status
    ];

    public $timestamps = true; // Enable timestamps

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    // Optional: Relationship to the admin who updated the status
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}