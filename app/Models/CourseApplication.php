<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'study_programme',
        'course',
        'ol_certificate',
        'al_certificate',
        'diploma_certificates',
        'degree_certificate',
        'transcript_certificate',
        'other_certificates',
    ];

    protected $casts = [
        'diploma_certificates' => 'array',
        'other_certificates' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}