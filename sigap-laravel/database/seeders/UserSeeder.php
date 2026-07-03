<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $admin = User::create([
        'name'     => 'Admin SIGAP',
        'email'    => 'admin@sigap.com',
        'password' => Hash::make('password'),
    ]);
    $admin->assignRole('admin');

    $warga = User::create([
        'name'     => 'Warga Demo',
        'email'    => 'warga@sigap.com',
        'password' => Hash::make('password'),
    ]);
    $warga->assignRole('user');
}
}
