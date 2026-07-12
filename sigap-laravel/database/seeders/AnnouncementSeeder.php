<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@sigap.com')->first();

        Announcement::create([
            'title'      => 'Selamat Datang di SIGAP',
            'content'    => 'Laporkan kerusakan infrastruktur di sekitar Anda melalui aplikasi ini. Tim kami akan menindaklanjuti secepatnya.',
            'is_pinned'  => true,
            'created_by' => $admin->id,
        ]);

        Announcement::create([
            'title'      => 'Jadwal Pemeliharaan Sistem',
            'content'    => 'Sistem akan mengalami maintenance rutin setiap hari Minggu pukul 00.00 - 02.00 WIB.',
            'is_pinned'  => false,
            'created_by' => $admin->id,
        ]);
    }
}
