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
    ];

    public $timestamps = false; // Keep timestamps disabled

    protected $dates = ['created_at']; // Cast created_at as a date

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}