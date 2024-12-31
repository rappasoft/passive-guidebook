<?php

namespace Database\Seeders;

use App\Models\SocialCasino;
use App\Models\SocialCasinoNews;
use App\Models\SocialCasinoPromotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialCasinoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zula = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Zula',
            'slug' => 'zula',
            'url' => 'https://www.zulacasino.com',
            'referral_url' => 'https://www.zulacasino.com/signup/2e7a033d-a231-4432-83e0-ec794eb0fbc8',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => 1,
            'daily_bonus_reset_timing' => 'Once a day. Resets at 6pm PT',
            'daily_location' => '- Log in<br/>- Click "coin store" on the top<br/>- If available it will be under "Claim free rewards"',
            'signup_bonus' => '10 SC',
            'referral_bonus' => 'On referrals first purchase',
            'minimum_redemption' => '$50',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => ['MI', 'GA', 'WA', 'ID'],
            'canada_excluded' => null,
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Joker\'s Jewels Wild',
            'best_playthrough_game_url' => 'https://www.zulacasino.com/play/50015/50015',
            'notes' => '- No deposit welcome bonus<br/>- Fortune Coin sister company',
            'terms_url' => 'https://www.zulacasino.com/terms-and-conditions',
        ]);

        if (app()->environment('local')) {
            $zula->promotions()->saveMany([
                new SocialCasinoPromotion([
                    'title'         => 'HOT VS COLD SLOTS: The Ultimate Slots Showdown!',
                    'url'           => 'https://www.zulacasino.com/promotions/promo/the-ultimate-slots-showdown',
                    'rewards'       => 'GC 500M + SC 3,000',
                    'dollar_value'  => 3000,
                    'rewards_label' => 'Prize Pool',
                    'expires_at'    => now()->addMonths(5),
                ]),

                new SocialCasinoPromotion([
                    'title'         => 'HOT VS COLD SLOTS: The Ultimate Slots Showdown!',
                    'url'           => 'https://www.zulacasino.com/promotions/promo/the-ultimate-slots-showdown',
                    'rewards'       => 'GC 500M + SC 3,000',
                    'dollar_value'  => 3000,
                    'rewards_label' => 'Prize Pool',
                    'expires_at'    => now()->subDays(5),
                ]),
            ]);

            $zula->promotions()->saveMany([
                new SocialCasinoPromotion([
                    'type'         => SocialCasinoPromotion::TYPE_BONUS,
                    'title'        => 'BONUS',
                    'url'          => 'https://www.zulacasino.com/promotions/promo/the-ultimate-slots-showdown',
                    'rewards'      => '1 SC',
                    'dollar_value' => 1,
                    'expires_at'   => now()->addMonths(5),
                ]),

                new SocialCasinoPromotion([
                    'type'         => SocialCasinoPromotion::TYPE_BONUS,
                    'title'        => 'BONUS',
                    'url'          => 'https://www.zulacasino.com/promotions/promo/the-ultimate-slots-showdown',
                    'rewards'      => '2 SC',
                    'dollar_value' => 2,
                    'expires_at'   => now()->subDays(5),
                ]),
            ]);

            $zula->news()->saveMany([
                new SocialCasinoNews([
                    'title' => 'Crazy News Article',
                    'url'   => 'https://google.com',
                ]),
                new SocialCasinoNews([
                    'title' => 'Crazy News Article 2',
                    'url'   => 'https://google.com',
                ]),
            ]);
        }

        //        SocialCasino::create([
        //            'active' => true,
        //            'tier' => 1,
        //            'name' => '',
        //            'slug' => '',
        //            'url' => '',
        //            'referral_url' => '',
        //            'sweeps_extension_eligible' => true,
        //            'daily_bonus' => 1,
        //            'daily_bonus_reset_timing' => '',
        //            'daily_location' => '',
        //            'signup_bonus' => '',
        //            'referral_bonus' => '',
        //            'minimum_redemption' => '',
        //            'token_type' => '',
        //            'token_amount_per_dollar' => 1,
        //            'real_money_payout' => true,
        //            'usa_allowed' => true,
        //            'canada_allowed' => false,
        //            'usa_excluded' => ['MI', 'GA', 'WA', 'ID'],
        //            'canada_excluded' => null,
        //            'redemption_time' => '',
        //            'must_play_before_redemption' => true,
        //            'best_playthrough_game' => '',
        //            'best_playthrough_game_url' => '',
        //            'notes' => '',
        //            'terms_url' => '',
        //        ]);

        //        SocialCasino::create([
        //            'active' => true,
        //            'tier' => 1,
        //            'name' => '',
        //            'slug' => '',
        //            'url' => '',
        //            'referral_url' => '',
        //            'sweeps_extension_eligible' => true,
        //            'daily_bonus' => 1,
        //            'daily_bonus_reset_timing' => '',
        //            'signup_bonus' => '',
        //            'referral_bonus' => '',
        //            'minimum_redemption' => '',
        //            'token_type' => '',
        //            'token_amount_per_dollar' => 1,
        //            'real_money_payout' => true,
        //            'usa_allowed' => true,
        //            'canada_allowed' => false,
        //            'usa_excluded' => ['MI', 'GA', 'WA', 'ID'],
        //            'canada_excluded' => null,
        //            'redemption_time' => '',
        //            'must_play_before_redemption' => true,
        //            'best_playthrough_game' => '',
        //            'best_playthrough_game_url' => '',
        //            'notes' => '',
        //            'terms_url' => '',
        //        ]);
    }
}
