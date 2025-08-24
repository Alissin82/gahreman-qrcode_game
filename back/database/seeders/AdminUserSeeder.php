<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'email' => 'admin@example.com',
                'phone' => '09120000000',
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        if (!\Spatie\Permission\Models\Role::where('name', 'super_admin')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'super_admin']);
        }

        $user->assignRole('super_admin');
    }
}
