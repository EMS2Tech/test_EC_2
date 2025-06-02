<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('title', ['Mr', 'Mrs', 'Miss', 'Rev'])->nullable();
            $table->string('full_name');
            $table->string('name_with_initials');
            $table->date('birthday');
            $table->string('nationality')->default('Sri Lanka');
            $table->string('nic_number')->nullable();
            $table->string('nic_photo')->nullable();
            $table->string('other_nationality')->nullable();
            $table->string('passport_photo')->nullable();
            $table->string('photograph');
            $table->text('address');
            $table->string('contact_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address');
            $table->boolean('application_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}