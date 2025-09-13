<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'nic_number',
        'complaint_type',
        'message',
        'reported_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // No casting needed for now, add if required
    ];

    /**
     * Get the user who reported the complaint.
     */
    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the associated application based on nic_number or passport_number.
     */
    public function getApplicationAttribute()
    {
        return Application::where('nic_number', $this->nic_number)
                         ->orWhere('passport_number', $this->nic_number)
                         ->first();
    }
}