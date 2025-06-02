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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('study_programme', ['bachelors', 'higher_diploma', 'diploma', 'postgraduate_diploma']);
            $table->string('course');
            $table->string('ol_certificate')->nullable();
            $table->string('al_certificate')->nullable();
            $table->json('diploma_certificates')->nullable();
            $table->string('degree_certificate')->nullable();
            $table->string('transcript_certificate')->nullable();
            $table->json('other_certificates')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_applications');
    }
}