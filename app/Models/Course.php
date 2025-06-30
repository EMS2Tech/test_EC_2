<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Course extends Model
  {
      protected $fillable = ['program_id', 'course_name', 'code', 'short_name'];

      public function studyProgram()
      {
          return $this->belongsTo(StudyProgram::class, 'program_id');
      }

      public function batches()
      {
          return $this->hasMany(Batch::class, 'course_id');
      }
  }