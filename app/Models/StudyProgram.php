<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class StudyProgram extends Model
  {
      protected $fillable = ['code','program_name'];

      public function courses()
      {
          return $this->hasMany(Course::class, 'program_id');
      }
  }