<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportStatusLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $warga = User::where('email', 'warga@sigap.com')->first();
        $admin = User::where('email', 'admin@sigap.com')->first();

        // Laporan 1 — sudah selesai (punya track record lengkap)
        $report1 = Report::create([
            'user_id'          => $warga->id,
            'category_id'      => 1,
            'title'            => 'Jalan berlubang di Jl. Sudirman',
            'description'      => 'Terdapat lubang besar di tengah jalan yang membahayakan',
            'location_address' => 'Jl. Sudirman No. 45, Bandung',
            'status'           => 'selesai',
            'urgency'          => 'darurat',
        ]);

        // Track record laporan 1
        ReportStatusLog::insert([
            [
                'report_id'        => $report1->id,
                'changed_by'       => $admin->id,
                'status'           => 'diterima',
                'notes'            => 'Laporan diterima dan akan segera ditinjau',
                'task_description' => null,
                'created_at'       => now()->subDays(5),
                'updated_at'       => now()->subDays(5),
            ],
            [
                'report_id'        => $report1->id,
                'changed_by'       => $admin->id,
                'status'           => 'ditinjau',
                'notes'            => 'Tim sudah turun ke lapangan',
                'task_description' => 'Petugas melakukan pengecekan lokasi lubang jalan',
                'created_at'       => now()->subDays(4),
                'updated_at'       => now()->subDays(4),
            ],
            [
                'report_id'        => $report1->id,
                'changed_by'       => $admin->id,
                'status'           => 'in_progress',
                'notes'            => 'Proses perbaikan dimulai',
                'task_description' => 'Tim Dinas PU sedang menambal lubang jalan',
                'created_at'       => now()->subDays(2),
                'updated_at'       => now()->subDays(2),
            ],
            [
                'report_id'        => $report1->id,
                'changed_by'       => $admin->id,
                'status'           => 'selesai',
                'notes'            => 'Perbaikan selesai',
                'task_description' => 'Lubang jalan telah berhasil ditambal',
                'created_at'       => now()->subDay(),
                'updated_at'       => now()->subDay(),
            ],
        ]);

        // Laporan 2 — sedang in_progress
        $report2 = Report::create([
            'user_id'          => $warga->id,
            'category_id'      => 2,
            'title'            => 'Lampu jalan mati di perumahan',
            'description'      => 'Lampu jalan RT 05 sudah mati 3 hari',
            'location_address' => 'Perumahan Griya Asri Blok B, Bandung',
            'status'           => 'in_progress',
            'urgency'          => 'penting',
        ]);

        ReportStatusLog::insert([
            [
                'report_id'        => $report2->id,
                'changed_by'       => $admin->id,
                'status'           => 'diterima',
                'notes'            => 'Laporan diterima',
                'task_description' => null,
                'created_at'       => now()->subDays(2),
                'updated_at'       => now()->subDays(2),
            ],
            [
                'report_id'        => $report2->id,
                'changed_by'       => $admin->id,
                'status'           => 'in_progress',
                'notes'            => 'Sedang ditangani PLN',
                'task_description' => 'Teknisi PLN sedang melakukan penggantian lampu',
                'created_at'       => now()->subDay(),
                'updated_at'       => now()->subDay(),
            ],
        ]);

        // Laporan 3 — baru masuk
        Report::create([
            'user_id'          => $warga->id,
            'category_id'      => 3,
            'title'            => 'Saluran air tersumbat',
            'description'      => 'Got depan gang mampet menyebabkan banjir kecil',
            'location_address' => 'Gang Mawar No. 3, Bandung',
            'status'           => 'diterima',
            'urgency'          => 'normal',
        ]);
    }
}
