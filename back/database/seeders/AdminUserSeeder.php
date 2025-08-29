<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super = User::updateOrCreate(
            [
                'email' => 'super@iraniom.ir',
            ],
            [
                'name' => 'مدیریت',
                'phone' => '09120000000',
                'password' => Hash::make('password'),
            ]
        );

        $admin = User::updateOrCreate(
            [
                'email' => 'admin@iraniom.ir',
            ],
            [
                'name' => 'داور',
                'phone' => '09130000000',
                'password' => Hash::make('password'),
            ]
        );
        $superRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $extraPermissions = [
            ...Permission::where('name', 'like', '%extra%')->pluck('name')->toArray(),
            ...Permission::where('name', 'like', '%review%')->pluck('name')->toArray(),
            ...Permission::where('name', 'like', '%team_coin%')->pluck('name')->toArray(),
        ];
        $adminRole->syncPermissions($extraPermissions);


        $admin->assignRole('admin');
        $super->assignRole('super_admin');
    }
}
