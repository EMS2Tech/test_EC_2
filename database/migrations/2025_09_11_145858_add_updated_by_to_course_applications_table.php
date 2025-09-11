<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedByToCourseApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('course_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')->nullable()->after('rejection_reason');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('course_applications', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });
    }
}