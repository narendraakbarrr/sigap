<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReportCategory;

class ReportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $categories = [
            [
                'name' => 'Jalan & Trotoar',
                'icon' => 'road',
                'description' => 'Kerusakan jalan atau trotoar'
            ],
            [
                'name' => 'Penerangan',
                'icon' => 'lightbulb',
                'description' => 'Lampu jalan mati atau rusak'
            ],
            [
                'name' => 'Drainase',
                'icon' => 'water',
                'description' => 'Saluran air tersumbat atau banjir'
            ],
            [
                'name' => 'Fasilitas Umum',
                'icon' => 'building',
                'description' => 'Kerusakan taman, halte, atau fasilitas umum'
            ],
            [
                'name' => 'Kebersihan',
                'icon' => 'trash',
                'description' => 'Sampah menumpuk atau lingkungan kotor'
            ],
            [
                'name' => 'Listrik',
                'icon' => 'electricity',
                'description' => 'Gangguan listrik, kabel, atau tiang listrik'
            ],
            [
                'name' => 'Pohon',
                'icon' => 'park',
                'description' => 'Pohon tumbang, pohon miring, atau ranting berbahaya'
            ],
            [
                'name' => 'Lalu Lintas',
                'icon' => 'traffic',
                'description' => 'Lampu lalu lintas, rambu, atau marka jalan'
            ],
            [
                'name' => 'Air Bersih',
                'icon' => 'faucet',
                'description' => 'Kebocoran pipa atau gangguan distribusi air'
            ],
            [
                'name' => 'Infrastruktur',
                'icon' => 'construction',
                'description' => 'Kerusakan jembatan atau bangunan umum'
            ],
            [
                'name' => 'Keamanan',
                'icon' => 'shield',
                'description' => 'Gangguan keamanan atau fasilitas keamanan'
            ],
            [
                'name' => 'Lingkungan',
                'icon' => 'nature',
                'description' => 'Pencemaran lingkungan atau kerusakan ruang hijau'
            ],
            [
                'name' => 'Telekomunikasi',
                'icon' => 'wifi',
                'description' => 'Gangguan jaringan atau kabel telekomunikasi'
            ],
            [
                'name' => 'Bencana',
                'icon' => 'warning',
                'description' => 'Bencana alam seperti banjir, longsor, atau angin kencang'
            ],
            [
                'name' => 'Lainnya',
                'icon' => 'category',
                'description' => 'Laporan di luar kategori yang tersedia'
            ],
        ];

        foreach ($categories as $c) {
            ReportCategory::create($c);
        }
    }
}
