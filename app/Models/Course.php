<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'program_id', 'course_name', 'short_name'];

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'program_id');
    }
}