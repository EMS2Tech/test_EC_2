<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'subject_id',
        'subject_name',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subject');
    }
}