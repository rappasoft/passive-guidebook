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
            'description' => 'When it comes to guaranteed interest on your savings, options like high-yield savings accounts, Certificates of Deposit (CDs), and money market accounts offer secure and reliable ways to grow your money. High-yield savings accounts provide flexibility with competitive interest rates and full access to funds, making them ideal for short-term goals. CDs, on the other hand, offer higher fixed rates but require you to lock your money for a set period, rewarding you with stability and better returns over time. Money market accounts combine elements of both, offering higher rates than traditional savings with limited transaction flexibility. All these accounts are federally insured, ensuring your deposits are safe while your money grows steadily with minimal risk.',
            'account_types' => [
                'HYSA',
                'CD',
                'Money Market',
            ],
            'slug' => PassiveSource::HYSA,
            'passive_percentage' => 100,
            'sort' => 1,
            'upfront_cost' => null,
            'level' => 1,
        ]);

        PassiveSource::create([
            'name' => 'Dividend Stocks',
            'slug' => PassiveSource::DIVIDENDS,
            'description' => 'Dividend stocks are an excellent option for generating passive income because they provide regular payouts in addition to the potential for capital appreciation. These stocks represent shares of companies that distribute a portion of their earnings to shareholders, typically on a quarterly basis. Dividend-paying companies are often well-established and financially stable, making them a relatively reliable source of income. Unlike fixed-income investments like CDs or bonds, dividend stocks also offer the opportunity for your initial investment to grow in value over time, allowing you to build wealth while earning passive income. By reinvesting dividends, you can further accelerate your portfolio’s growth through the power of compounding. This combination of steady income and potential long-term gains makes dividend stocks a versatile and attractive option for passive income seekers.',
            //            'account_types' => [
            //                'Brokerage',
            //                '401a',
            //                '401k',
            //                '403B',
            //                '457b',
            //                '529',
            //                'IRA',
            //                'HSA',
            //                'Mutual Fund',
            //                'Cash ISA',
            //            ],
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
