<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            AdminUserSeeder::class,
        ]);
        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
        ]);
    }
}
