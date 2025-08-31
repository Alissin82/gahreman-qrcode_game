<?php

namespace Database\Seeders;

use App\Imports\OldDataImport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
        ]);

        $excel = Storage::disk()->path('old-data/data.xlsx');
        $sheets = Excel::toCollection(new OldDataImport(), $excel);

        RegionSeeder::$regions = $sheets[0]->toArray();
        ActionSeeder::$actions = $sheets[1]->toArray();
        TasksSeeder::$tasks = $sheets[2]->toArray();

        $this->call([
            AdminUserSeeder::class,
            TeamsSeeder::class,
            RegionSeeder::class,
            ActionSeeder::class,
            TasksSeeder::class,
        ]);
    }
}
