<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreatePaymentsTable extends Migration
  {
      public function up()
      {
          Schema::create('payments', function (Blueprint $table) {
              $table->id();
              $table->foreignId('user_id')->constrained()->onDelete('cascade');
              $table->foreignId('application_id')->constrained()->onDelete('cascade');
              $table->string('status')->default('Pending');
              $table->string('payment_slip')->nullable();
              $table->timestamp('created_at')->useCurrent();
          });
      }

      public function down()
      {
          Schema::dropIfExists('payments');
      }
  }