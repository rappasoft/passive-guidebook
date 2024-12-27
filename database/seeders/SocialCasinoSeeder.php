<?php

namespace Database\Seeders;

use App\Models\SocialCasino;
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
        SocialCasino::create([
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
