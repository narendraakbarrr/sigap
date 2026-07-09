<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_status_logs', function (Blueprint $table) {
            // task_description: deskripsi tindakan yang sedang dilakukan
            // contoh: "Jalan sedang diperbaiki oleh tim Dinas PU"
            $table->text('task_description')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('report_status_logs', function (Blueprint $table) {
            $table->dropColumn('task_description');
        });
    }
};
