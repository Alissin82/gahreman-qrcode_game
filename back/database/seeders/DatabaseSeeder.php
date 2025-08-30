<?php

namespace Database\Seeders;

use App\Imports\OldDataImport;
use App\Models\Action;
use App\Models\Coin;
use App\Models\Mission;
use App\Models\Region;
use App\Models\ScoreCard;
use App\Models\Team;
use App\Models\TeamAdmins;
use App\Models\TeamUsers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

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

        $this->call([
            AdminUserSeeder::class,
        ]);

        if (config('app.env') === 'local') {
            $region = Region::create([
               'name' => fake()->word,
            ]);
            $action = Action::create([
                'name' => fake()->word(),
                'region_id' => $region->id,
                'release' => now()
            ]);
            Mission::create([
                'action_id' => $action->id,
                'title' => fake()->word(),
                'score' => 0,
            ]);

            Coin::create([
                'name' => "کارت 100 سکه ای",
                'coin' => 100,
            ]);

            ScoreCard::create([
                'name' => "کارت 200 امتیازی",
                'score' => 200,
            ]);
        }

        // تاریخ شروع برای داده‌هایی که قرار است وارد شوند
        $startDate = (new Jalalian(1404, 2, 11))->toCarbon();

        // مربی پیش فرض مرد
        $boysTeamAdmin = [
            'name' => " مهدی",
            'family' => 'جوانمردی',
            'gender' => true,
            'start' => $startDate
        ];

        // مربی پیش فرض خانم
        $girlsTeamAdmin = [
            'name' => "اعظم",
            'family' => 'بلکامه',
            'gender' => false,
            'start' => $startDate
        ];

        $excels = [];
        $excels[] = [
            'path' => Storage::disk('local')->path('old-data/boys.xlsx'),
            'gender' => true, // for male
            'admin' => $boysTeamAdmin,
        ];
        $excels[] = [
            'path' => Storage::disk('local')->path('old-data/girls.xlsx'),
            'gender' => false, // for females
            'admin' => $girlsTeamAdmin,
        ];
        foreach ($excels as $excel) {
            $boysSheets = Excel::toCollection(new OldDataImport(), $excel['path']);
            foreach ($boysSheets as $sheet) {
                // هر sheet یک تیم هست

                $sheetArray = $sheet->toArray();
                array_shift($sheetArray); // just to surpass title
                array_shift($sheetArray); // just to surpass headers
                $firstRow = $sheetArray[0];
                // گروهان
                $companyName = $firstRow[3];
                // تعداد پویندگان
                $followersCount = $firstRow[4];

                $team = Team::create([
                    'name' => $companyName,
                    'gender' => $excel['gender'],
                ]);

                TeamAdmins::create(array_merge($excel['admin'], [
                    'team_id' => $team->id,
                ]));

                $teamUsers = [];
                foreach ($sheetArray as $row) {
                    $now = now();
                    $teamUsers[] = [
                        'name' => $row[2],
                        'family' => '',
                        'team_id' => $team->id,
                        'city' => $row[0],
                        'province' => 'اصفهان',
                        'gender' => $excel['gender'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                TeamUsers::insert($teamUsers);
            }
        }
    }
}
