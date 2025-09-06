<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseApplication extends Model
{
    protected $fillable = [
        'user_id',
        'study_programme_id',
        'course_id',
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

    // Ensure timestamps are enabled (default in Laravel)
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_programme_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Optional: Method to get active batches for the associated course
    public function getActiveBatchesAttribute()
    {
        return $this->course->batches()->where('start_date', '<=', now())->where('end_date', '>=', now())->get();
    }
}