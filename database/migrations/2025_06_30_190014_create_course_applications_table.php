<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('course_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('study_programme_id');
            $table->unsignedBigInteger('course_id');
            $table->string('ol_certificate')->nullable();
            $table->string('al_certificate')->nullable();
            $table->json('diploma_certificates')->nullable();
            $table->string('degree_certificate')->nullable();
            $table->string('transcript_certificate')->nullable();
            $table->json('other_certificates')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('study_programme_id')->references('id')->on('study_programs')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_applications');
    }
}