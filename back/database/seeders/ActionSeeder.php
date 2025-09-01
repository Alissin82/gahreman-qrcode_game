<?php

namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    static array $actions = [
        [
            'name' => 'Action 1',
            'score' => 10,
        ],
        [
            'name' => 'Action 2',
            'score' => 20,
        ],
        [
            'name' => 'Action 3',
            'score' => 30,
        ],
    ];

    public function run(): void
    {
        // skip header
        array_shift(self::$actions);

        $header = [
            'id',
            'name',
            'score',
            'region_id',
        ];

        foreach (self::$actions as $action) {
            $action = array_combine($header, $action);

            Action::create([
                'id' => $action['id'],
                'name' => $action['name'],
                'score' => $action['score'],
                'region_id' => $action['region_id'],
            ]);
        }
    }
}
