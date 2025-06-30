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
}