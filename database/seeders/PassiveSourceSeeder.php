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
            'name' => 'Custom',
            'short_name' => 'Custom',
            'slug' => PassiveSource::CUSTOM,
            'passive_percentage' => 0,
            'sort' => 0,
            'upfront_cost' => null,
            'level' => 0,
        ]);

        PassiveSource::create([
            'name' => 'Savings Accounts',
            'short_name' => 'Savings Accounts',
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
            'sort' => 3,
            'upfront_cost' => null,
            'level' => 1,
        ]);

        PassiveSource::create([
            'name' => 'Social Casinos',
            'slug' => PassiveSource::SOCIAL_CASINOS,
            'sort' => 0,
            'passive_percentage' => 60,
            'upfront_cost' => 0,
            'level' => 2,
        ]);

        PassiveSource::create([
            'name' => 'Credit Card Hacking',
            'slug' => 'credit-card-hacking',
            'sort' => 1,
            'passive_percentage' => 60,
            'upfront_cost' => 0,
            'level' => 2,
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
