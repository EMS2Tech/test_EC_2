<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchIdToCourseApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('course_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('batch_id')->nullable()->after('course_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('course_applications', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });
    }
}