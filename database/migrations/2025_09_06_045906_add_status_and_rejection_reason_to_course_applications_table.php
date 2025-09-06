<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndRejectionReasonToCourseApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('course_applications', function (Blueprint $table) {
            $table->string('status')->default('Pending'); // Default to 'Pending' for existing and new records
            $table->text('rejection_reason')->nullable(); // Nullable to allow null values for non-rejected applications
        });
    }

    public function down()
    {
        Schema::table('course_applications', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });
    }
}