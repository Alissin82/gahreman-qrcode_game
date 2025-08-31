<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    static array $regions = [
        [
            'id' => 1,
            'name' => 'Region 1',
            'x' => 0,
            'y' => 0,
            'order' => 0,
            'lockable' => false,
            'locked' => false,
        ],
        [
            'id' => 2,
            'name' => 'Region 2',
            'x' => 1,
            'y' => 1,
            'order' => 1,
            'lockable' => false,
            'locked' => false,
        ],
        [
            'id' => 3,
            'name' => 'Region 3',
            'x' => 2,
            'y' => 2,
            'order' => 2,
            'lockable' => false,
            'locked' => false,
        ],
        [
            'id' => 4,
            'name' => 'Region 4',
            'x' => 3,
            'y' => 3,
            'order' => 3,
            'lockable' => false,
            'locked' => false,
        ],
        [
            'id' => 5,
            'name' => 'Region 5',
            'x' => 4,
            'y' => 4,
            'order' => 4,
            'lockable' => false,
            'locked' => false,
        ],
    ];

    public function run(): void
    {
        // skip header
        array_shift(self::$regions);

        $header = [
            'id',
            'name',
            'x',
            'y',
            'order',
            'lockable',
            'locked',
        ];

        foreach (self::$regions as $region) {
            $region = array_combine($header, $region);
            Region::create([
                'id' => $region['id'],
                'name' => $region['name'],
                'x' => $region['x'],
                'y' => $region['y'],
                'order' => $region['order'],
                'lockable' => $region['lockable'],
                'locked' => $region['locked'],
            ]);
        }
    }
}
