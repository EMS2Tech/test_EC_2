<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequiredDocumentsToStudyProgramsTable extends Migration
{
    public function up()
    {
        Schema::table('study_programs', function (Blueprint $table) {
            $table->json('required_documents')->nullable()->after('program_name');
        });
    }

    public function down()
    {
        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropColumn('required_documents');
        });
    }
};
