<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [
            'view reports','create reports','edit reports','delete reports',
            'update report status',
            'view categories','create categories','edit categories','delete categories',
            'view users','edit users','delete users',
        ];
        foreach ($permissions as $p) Permission::create(['name' => $p]);

        Role::create(['name' => 'admin'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'user'])->givePermissionTo([
            'view reports','create reports','edit reports','delete reports'
        ]);
    }
}
