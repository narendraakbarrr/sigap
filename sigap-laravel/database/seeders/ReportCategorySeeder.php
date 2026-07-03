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
            ['name'=>'Jalan & Trotoar', 'icon'=>'road',      'description'=>'Kerusakan jalan atau trotoar'],
            ['name'=>'Penerangan',      'icon'=>'lightbulb',  'description'=>'Lampu jalan mati atau rusak'],
            ['name'=>'Drainase',        'icon'=>'water',       'description'=>'Saluran air tersumbat'],
            ['name'=>'Fasilitas Umum',  'icon'=>'building',   'description'=>'Kerusakan taman atau halte'],
            ['name'=>'Kebersihan',      'icon'=>'trash',       'description'=>'Sampah menumpuk'],
        ];
        foreach ($categories as $c) ReportCategory::create($c);
    }
}
