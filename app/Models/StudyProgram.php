<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $fillable = [
        'code',
        'program_name',
        'required_documents', // Added to allow mass assignment
    ];

    protected $casts = [
        'required_documents' => 'array', // Cast to array for JSON storage
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'program_id');
    }
    public function courseApplications()
    {
        return $this->hasMany(CourseApplication::class, 'study_programme_id');
    }
}