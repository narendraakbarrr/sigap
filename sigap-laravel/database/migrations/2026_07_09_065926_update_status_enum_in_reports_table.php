<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: ubah enum langsung via raw query
        DB::statement("ALTER TABLE reports MODIFY COLUMN status
            ENUM(
                'diterima',
                'ditinjau',
                'in_progress',
                'selesai',
                'ditolak'
            ) NOT NULL DEFAULT 'diterima'"
        );
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE reports MODIFY COLUMN status
            ENUM(
                'diterima',
                'diproses',
                'selesai',
                'ditolak'
            ) NOT NULL DEFAULT 'diterima'"
        );
    }
};
