<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyProgramsTable extends Migration
{
    public function up()
    {
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('program_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('study_programs');
        \DB::unprepared('DROP TRIGGER IF EXISTS before_insert_study_programs');
    }
}