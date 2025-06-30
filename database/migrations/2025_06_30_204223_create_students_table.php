<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreateStudentsTable extends Migration
  {
      public function up()
      {
          Schema::create('students', function (Blueprint $table) {
              $table->id();
              $table->unsignedBigInteger('user_id'); // Add user_id as foreign key
              $table->string('student_id')->unique();
              $table->string('full_name');
              $table->timestamps();

              $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          });
      }

      public function down()
      {
          Schema::dropIfExists('students');
      }
  }