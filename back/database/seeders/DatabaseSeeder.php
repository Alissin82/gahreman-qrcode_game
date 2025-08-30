<?php

namespace Database\Seeders;

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
            $team = Team::create([
                'name' => fake()->name,
                'color' => "#ff0000",
                'bio' => "ما می‌توانیم",
                'score' => 0,
                'coin' => 0,
                'phone' => "09138884444",
                'hash' => "1234567812345678",
                'gender' => fake()->boolean,
            ]);
            TeamUsers::create([
                'team_id' => $team->id,
                'name' => fake()->firstName,
                'family' => fake()->lastName,
            ]);
            TeamAdmins::create([
                'team_id' => $team->id,
                'name' => fake()->firstName,
                'family' => fake()->lastName,
            ]);

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
    }
}
