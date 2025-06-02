<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNicNumberAndOtherNationalityToApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('nic_number')->nullable()->after('nationality');
            $table->string('other_nationality')->nullable()->after('nic_photo');
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['nic_number', 'other_nationality']);
        });
    }
}