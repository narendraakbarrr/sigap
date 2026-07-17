<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement("ALTER TABLE reports RENAME COLUMN status TO status_old");
            DB::statement("ALTER TABLE reports ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'diterima'");
            DB::statement("UPDATE reports SET status = status_old");
            DB::statement("ALTER TABLE reports DROP COLUMN status_old");

            return;
        }

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
        if (DB::getDriverName() === 'sqlite') {
            DB::statement("ALTER TABLE reports RENAME COLUMN status TO status_old");
            DB::statement("ALTER TABLE reports ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'diterima'");
            DB::statement("UPDATE reports SET status = status_old");
            DB::statement("ALTER TABLE reports DROP COLUMN status_old");

            return;
        }

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
