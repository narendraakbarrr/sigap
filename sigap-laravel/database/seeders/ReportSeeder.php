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

        $report1 = Report::create([
            'user_id'          => $warga->id,
            'category_id'      => 1,
            'title'            => 'Jalan berlubang di Jl. Sudirman',
            'description'      => 'Terdapat lubang besar di tengah jalan yang membahayakan',
            'location_address' => 'Jl. Sudirman No. 45, Bandung',
            'status'           => 'selesai',
            'urgency'          => 'darurat',
        ]);
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

        Report::create([
            'user_id'          => $warga->id,
            'category_id'      => 3,
            'title'            => 'Saluran air tersumbat',
            'description'      => 'Got depan gang mampet menyebabkan banjir kecil',
            'location_address' => 'Gang Mawar No. 3, Bandung',
            'status'           => 'diterima',
            'urgency'          => 'normal',
        ]);

        $additionalReports = [
            [
                'category_id'      => 1,
                'title'            => 'Aspal retak parah di Jl. Cihampelas',
                'description'      => 'Aspal retak dan bergelombang cukup panjang, rawan kecelakaan motor',
                'location_address' => 'Jl. Cihampelas No. 12, Bandung',
                'urgency'          => 'penting',
                'status'           => 'ditinjau',
                'days_ago'         => 4,
            ],
            [
                'category_id'      => 2,
                'title'            => 'Tiang listrik miring pasca hujan angin',
                'description'      => 'Tiang listrik di pinggir jalan miring dan berisiko roboh',
                'location_address' => 'Jl. Dago Pojok, Bandung',
                'urgency'          => 'darurat',
                'status'           => 'in_progress',
                'days_ago'         => 3,
            ],
            [
                'category_id'      => 3,
                'title'            => 'Drainase tersumbat sampah plastik',
                'description'      => 'Drainase penuh sampah plastik sehingga air menggenang tiap hujan',
                'location_address' => 'Jl. Cibaduyut Raya, Bandung',
                'urgency'          => 'normal',
                'status'           => 'selesai',
                'days_ago'         => 6,
            ],
            [
                'category_id'      => 1,
                'title'            => 'Jalan amblas di dekat jembatan',
                'description'      => 'Sebagian badan jalan amblas dekat jembatan kecil',
                'location_address' => 'Jl. Rajawali, Bandung',
                'urgency'          => 'darurat',
                'status'           => 'in_progress',
                'days_ago'         => 3,
            ],
            [
                'category_id'      => 2,
                'title'            => 'Lampu taman kota tidak menyala',
                'description'      => 'Lampu taman kota mati sejak seminggu lalu, area jadi gelap malam hari',
                'location_address' => 'Taman Cibeunying, Bandung',
                'urgency'          => 'normal',
                'status'           => 'diterima',
                'days_ago'         => 1,
            ],
            [
                'category_id'      => 3,
                'title'            => 'Banjir akibat gorong-gorong rusak',
                'description'      => 'Gorong-gorong rusak menyebabkan banjir setiap hujan deras',
                'location_address' => 'Jl. Kopo Sayati, Bandung',
                'urgency'          => 'darurat',
                'status'           => 'ditinjau',
                'days_ago'         => 2,
            ],
            [
                'category_id'      => 1,
                'title'            => 'Marka jalan pudar di persimpangan',
                'description'      => 'Marka jalan sudah pudar sehingga membingungkan pengendara',
                'location_address' => 'Simpang Jl. Ahmad Yani, Bandung',
                'urgency'          => 'normal',
                'status'           => 'selesai',
                'days_ago'         => 7,
            ],
            [
                'category_id'      => 2,
                'title'            => 'Kabel listrik menjuntai rendah',
                'description'      => 'Kabel listrik menjuntai rendah dan membahayakan pejalan kaki',
                'location_address' => 'Jl. Pasteur, Bandung',
                'urgency'          => 'darurat',
                'status'           => 'ditinjau',
                'days_ago'         => 2,
            ],
            [
                'category_id'      => 3,
                'title'            => 'Selokan meluap ke jalan raya',
                'description'      => 'Selokan meluap setiap hujan sehingga jalan raya tergenang',
                'location_address' => 'Jl. Soekarno Hatta, Bandung',
                'urgency'          => 'penting',
                'status'           => 'in_progress',
                'days_ago'         => 3,
            ],
            [
                'category_id'      => 1,
                'title'            => 'Lubang kecil bertambah banyak',
                'description'      => 'Beberapa lubang kecil muncul dan makin banyak setelah musim hujan',
                'location_address' => 'Jl. Buah Batu, Bandung',
                'urgency'          => 'normal',
                'status'           => 'diterima',
                'days_ago'         => 1,
            ],
            [
                'category_id'      => 2,
                'title'            => 'Lampu jalan berkedip-kedip',
                'description'      => 'Lampu jalan menyala tidak stabil, kadang mati kadang menyala',
                'location_address' => 'Jl. Setiabudi, Bandung',
                'urgency'          => 'normal',
                'status'           => 'ditinjau',
                'days_ago'         => 3,
            ],
            [
                'category_id'      => 3,
                'title'            => 'Saluran irigasi jebol',
                'description'      => 'Saluran irigasi jebol dan airnya masuk ke pemukiman warga',
                'location_address' => 'Jl. Ciwastra, Bandung',
                'urgency'          => 'darurat',
                'status'           => 'in_progress',
                'days_ago'         => 2,
            ],
            [
                'category_id'      => 1,
                'title'            => 'Jalan berlubang menyebabkan kecelakaan',
                'description'      => 'Sudah ada korban jatuh dari motor akibat lubang di jalan ini',
                'location_address' => 'Jl. Terusan Jakarta, Bandung',
                'urgency'          => 'darurat',
                'status'           => 'selesai',
                'days_ago'         => 8,
            ],
            [
                'category_id'      => 2,
                'title'            => 'Panel listrik terbuka di pinggir jalan',
                'description'      => 'Panel listrik terbuka tanpa penutup dan rawan tersengat',
                'location_address' => 'Jl. Antapani, Bandung',
                'urgency'          => 'penting',
                'status'           => 'diterima',
                'days_ago'         => 1,
            ],
            [
                'category_id'      => 3,
                'title'            => 'Got mampet karena tanah longsor kecil',
                'description'      => 'Got tertutup material longsoran tanah dari tebing kecil',
                'location_address' => 'Jl. Ledeng, Bandung',
                'urgency'          => 'penting',
                'status'           => 'ditinjau',
                'days_ago'         => 4,
            ],
            [
                'category_id'      => 1,
                'title'            => 'Aspal terkelupas dekat sekolah',
                'description'      => 'Aspal terkelupas di depan gerbang sekolah, membahayakan siswa',
                'location_address' => 'Jl. Cijerah, Bandung',
                'urgency'          => 'penting',
                'status'           => 'in_progress',
                'days_ago'         => 2,
            ],
            [
                'category_id'      => 2,
                'title'            => 'Lampu jalan padam total satu ruas jalan',
                'description'      => 'Seluruh lampu jalan di satu ruas padam total sejak semalam',
                'location_address' => 'Jl. Jakarta, Bandung',
                'urgency'          => 'darurat',
                'status'           => 'diterima',
                'days_ago'         => 1,
            ],
        ];

        foreach ($additionalReports as $data) {
            $report = Report::create([
                'user_id'          => $warga->id,
                'category_id'      => $data['category_id'],
                'title'            => $data['title'],
                'description'      => $data['description'],
                'location_address' => $data['location_address'],
                'status'           => $data['status'],
                'urgency'          => $data['urgency'],
            ]);

            $this->createStatusLogs($report, $admin, $data['status'], $data['days_ago']);
        }
    }

    private function createStatusLogs(Report $report, User $admin, string $finalStatus, int $daysAgo): void
    {
        $flow = [
            'diterima'    => [
                'notes'            => 'Laporan diterima dan akan segera ditinjau',
                'task_description' => null,
            ],
            'ditinjau'    => [
                'notes'            => 'Tim sudah turun ke lapangan untuk peninjauan',
                'task_description' => 'Petugas melakukan pengecekan lokasi',
            ],
            'in_progress' => [
                'notes'            => 'Proses penanganan sedang berlangsung',
                'task_description' => 'Tim lapangan sedang menindaklanjuti laporan',
            ],
            'selesai'     => [
                'notes'            => 'Penanganan telah selesai dilakukan',
                'task_description' => 'Laporan telah berhasil ditindaklanjuti dan selesai',
            ],
        ];

        $order = ['diterima', 'ditinjau', 'in_progress', 'selesai'];
        $finalIndex = array_search($finalStatus, $order);
        $statusesToLog = array_slice($order, 0, $finalIndex + 1);

        $totalSteps = count($statusesToLog);
        $logs = [];

        foreach ($statusesToLog as $i => $status) {

            $stepsRemaining = $totalSteps - 1 - $i;
            $createdAt = $stepsRemaining === 0
                ? now()->subDays(0)->subHours($daysAgo > 0 ? 1 : 0)
                : now()->subDays((int) ceil($daysAgo * $stepsRemaining / $totalSteps));

            $logs[] = [
                'report_id'        => $report->id,
                'changed_by'       => $admin->id,
                'status'           => $status,
                'notes'            => $flow[$status]['notes'],
                'task_description' => $flow[$status]['task_description'],
                'created_at'       => $createdAt,
                'updated_at'       => $createdAt,
            ];
        }

        ReportStatusLog::insert($logs);
    }
}
