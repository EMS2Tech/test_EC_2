<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('payments')
            ->where('status', 'Pending Verification')
            ->update(['status' => 'Pending']);
    }

    public function down(): void
    {
        DB::table('payments')
            ->where('status', 'Pending')
            ->update(['status' => 'Pending Verification']);
    }
};