<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\Mission;
use App\Models\Region;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    static array $actions = [];

    public function run(): void
    {
        // skip header
        array_shift(self::$actions);

        $header = [
            'id',
            'name',
            'region_id',
            'lock',
            'requirement_action_id',
            'end_scan_score',
            'qr_coin_10',
            'qr_coin_20',
            'qr_coin_30',
            'qr_coin_40',
            'qr_coin_50',
            'qr_score_10',
            'qr_score_20',
            'qr_score_30',
            'qr_score_40',
            'qr_score_50',
            'multi_question',
            'review'
        ];

        foreach (self::$actions as $action) {
            $action = array_combine($header, $action);
            if ($action['lock']) {
                Region::find($action['region_id'])->update([
                    'lockable' => true,
                ]);
            }

            Action::create([
                'id' => $action['id'],
                'name' => $action['name'],
                'region_id' => $action['region_id'],
            ]);

            Mission::create([
                'id' => $action['id'],
                'title' => $action['name'],
                'score' => $action['end_scan_score'],
                'action_id' => $action['id'],
            ]);
        }
    }
}
