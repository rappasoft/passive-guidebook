<?php

namespace Database\Seeders;

use App\Models\PassiveSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PassiveSourceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PassiveSource::create([
            'name' => 'Social Casinos',
            'slug' => PassiveSource::SOCIAL_CASINOS,
            'sort' => 0,
            'passive_percentage' => 75,
            'upfront_cost' => 0,
            'level' => 1,
        ]);

        PassiveSource::create([
            'name' => 'High-Yield Savings Accounts',
            'slug' => PassiveSource::HYSA,
            'passive_percentage' => 100,
            'sort' => 1,
            'upfront_cost' => null,
            'level' => 1,
        ]);

        PassiveSource::create([
            'name' => 'Dividend Stocks',
            'slug' => PassiveSource::DIVIDENDS,
            'passive_percentage' => 100,
            'sort' => 2,
            'upfront_cost' => null,
            'level' => 1,
        ]);

        //        PassiveSource::create([
        //            'name' => 'Grass.io',
        //            'slug' => 'grass-io',
        //            'passive_percentage' => 100,
        //            'sort' => 2,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
        //
        //        PassiveSource::create([
        //            'name' => '',
        //            'slug' => '',
        //            'passive_percentage' => 75,
        //            'sort' => 1,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
        //
        //        PassiveSource::create([
        //            'name' => '',
        //            'slug' => '',
        //            'passive_percentage' => 75,
        //            'sort' => 1,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
        //
        //        PassiveSource::create([
        //            'name' => '',
        //            'slug' => '',
        //            'passive_percentage' => 75,
        //            'sort' => 1,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
        //
        //        PassiveSource::create([
        //            'name' => '',
        //            'slug' => '',
        //            'passive_percentage' => 75,
        //            'sort' => 1,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
        //
        //        PassiveSource::create([
        //            'name' => '',
        //            'slug' => '',
        //            'passive_percentage' => 75,
        //            'sort' => 1,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
        //
        //        PassiveSource::create([
        //            'name' => '',
        //            'slug' => '',
        //            'passive_percentage' => 75,
        //            'sort' => 1,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
        //
        //        PassiveSource::create([
        //            'name' => '',
        //            'slug' => '',
        //            'passive_percentage' => 75,
        //            'sort' => 1,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
        //
        //        PassiveSource::create([
        //            'name' => '',
        //            'slug' => '',
        //            'passive_percentage' => 75,
        //            'sort' => 1,
        //            'upfront_cost' => 0,
        //            'level' => 1,
        //        ]);
    }
}
