<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyStudyProgramsTableRemoveTrigger extends Migration
{
    public function up()
    {
        Schema::table('study_programs', function (Blueprint $table) {
            // No structural change needed, just removing the trigger
        });

        // Drop the existing trigger
        \DB::unprepared('DROP TRIGGER IF EXISTS before_insert_study_programs');
    }

    public function down()
    {
        // Optionally re-add the trigger if rolling back
        \DB::unprepared('
            CREATE TRIGGER before_insert_study_programs
            BEFORE INSERT ON study_programs
            FOR EACH ROW
            BEGIN
                DECLARE max_code INT;
                SET max_code = (
                    SELECT CAST(SUBSTRING(MAX(code), 2) AS UNSIGNED)
                    FROM study_programs
                );
                IF max_code IS NULL THEN
                    SET max_code = 0;
                END IF;
                SET NEW.code = LPAD(max_code + 1, 3, "0");
            END;
        ');
    }
}