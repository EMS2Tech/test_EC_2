<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'student_id', 'full_name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function application()
    {
        return $this->hasOne(Application::class, 'user_id', 'user_id');
        // Explanation: hasOne(Application, 'user_id in applications', 'user_id in students')
        // This links students to their application via the shared user_id
    }
}