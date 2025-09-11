<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseApplication extends Model
{
    protected $fillable = [
        'user_id',
        'study_programme_id',
        'course_id',
        'batch_id',
        'status',
        'rejection_reason',
        'ol_certificate',
        'al_certificate',
        'degree_certificate',
        'transcript_certificate',
        'diploma_certificates',
        'other_certificates',
        'updated_by', // Added
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
        return $this->belongsTo(Course::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user->application->full_name ?? 'Unknown';
    }

    public function getStudyProgrammeNameAttribute()
    {
        return $this->studyProgram->program_name ?? 'N/A';
    }

    public function getCourseNameAttribute()
    {
        return $this->course->course_name ?? 'N/A';
    }

    public function getBatchNoAttribute()
    {
        $batch = $this->course->batches()->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
        return $batch ? $batch->batch_no : 'N/A';
    }

    public function getApplyDateAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    // Relationship to the admin who updated the status
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}