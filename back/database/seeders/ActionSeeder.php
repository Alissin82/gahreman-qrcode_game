<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\Mission;
use App\Models\Region;
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
            'name',
            'score',
        ];

        foreach (self::$actions as $action) {
            $action = array_combine($header, $action);

            // if ($action['lock']) {
            //     Region::find($action['region_id'])->update([
            //         'lockable' => true,
            //     ]);
            // }

            $action = Action::create([
                'name' => $action['name'],
                'score' => $action['score'],
                // 'region_id' => $action['region_id'],
            ]);

            Mission::create([
                'title' => $action['name'],
                'score' => $action['score'],
                'action_id' => $action->id,
            ]);
        }
    }
}
