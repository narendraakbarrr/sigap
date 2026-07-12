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
            'manage-reports', 'manage-categories', 'manage-users', 'view-dashboard',
            'create-report', 'edit-own-report', 'view-own-reports',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        Role::firstOrCreate(['name' => 'admin'])
            ->syncPermissions(['manage-reports', 'manage-categories', 'manage-users', 'view-dashboard']);

        Role::firstOrCreate(['name' => 'user'])
            ->syncPermissions(['create-report', 'edit-own-report', 'view-own-reports']
        );
    }
}
