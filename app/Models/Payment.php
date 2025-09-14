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
        'updated_by',
        'program', // New field for program type
        'amount',  // New field for payment amount
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}