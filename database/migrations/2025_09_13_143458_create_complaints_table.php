<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('nic_number')->nullable(); // NIC or Passport number, nullable for flexibility
            $table->string('complaint_type'); // Financial Problem, Exam Problem, etc.
            $table->text('message'); // Complaint message
            $table->unsignedBigInteger('reported_by'); // Admin who reported the complaint
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}