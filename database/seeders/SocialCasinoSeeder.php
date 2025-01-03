<?php

namespace Database\Seeders;

use App\Models\SocialCasino;
use App\Models\SocialCasinoNews;
use App\Models\SocialCasinoPromotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

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
            'usa_excluded' => ['MI', 'WA', 'ID'],
            'canada_excluded' => null,
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Joker\'s Jewels Wild',
            'best_playthrough_game_url' => 'https://www.zulacasino.com/play/50015/50015',
            'notes' => '- No deposit welcome bonus<br/>- Fortune Coin sister company',
            'terms_url' => 'https://www.zulacasino.com/terms-and-conditions',
        ]);

        $media = $zula->addMedia(public_path('img/casinos/zula.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        if (app()->environment('local')) {
            $zula->promotions()->saveMany([
                new SocialCasinoPromotion([
                    'title' => 'HOT VS COLD SLOTS: The Ultimate Slots Showdown!',
                    'url' => 'https://www.zulacasino.com/promotions/promo/the-ultimate-slots-showdown',
                    'rewards' => 'GC 500M + SC 3,000',
                    'dollar_value' => 3000,
                    'rewards_label' => 'Prize Pool',
                    'expires_at' => now()->addMonths(5),
                ]),

                new SocialCasinoPromotion([
                    'title' => 'HOT VS COLD SLOTS: The Ultimate Slots Showdown!',
                    'url' => 'https://www.zulacasino.com/promotions/promo/the-ultimate-slots-showdown',
                    'rewards' => 'GC 500M + SC 3,000',
                    'dollar_value' => 3000,
                    'rewards_label' => 'Prize Pool',
                    'expires_at' => now()->subDays(5),
                ]),
            ]);

            $zula->promotions()->saveMany([
                new SocialCasinoPromotion([
                    'type' => SocialCasinoPromotion::TYPE_BONUS,
                    'title' => 'BONUS',
                    'url' => 'https://www.zulacasino.com/promotions/promo/the-ultimate-slots-showdown',
                    'rewards' => '1 SC',
                    'dollar_value' => 1,
                    'expires_at' => now()->addMonths(5),
                ]),

                new SocialCasinoPromotion([
                    'type' => SocialCasinoPromotion::TYPE_BONUS,
                    'title' => 'BONUS',
                    'url' => 'https://www.zulacasino.com/promotions/promo/the-ultimate-slots-showdown',
                    'rewards' => '2 SC',
                    'dollar_value' => 2,
                    'expires_at' => now()->subDays(5),
                ]),
            ]);

            $zula->news()->saveMany([
                new SocialCasinoNews([
                    'title' => 'Crazy News Article',
                    'url' => 'https://google.com',
                ]),
                new SocialCasinoNews([
                    'title' => 'Crazy News Article 2',
                    'url' => 'https://google.com',
                ]),
            ]);
        }

        $fortuneCoins = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Fortune Coins',
            'slug' => 'fortune-coins',
            'url' => 'https://www.fortunecoins.com',
            'referral_url' => 'https://www.fortunecoins.com/signup/48f0767e-ea42-4c1d-8b81-e0ab9c169588',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => 1,
            'daily_bonus_reset_timing' => null,
            'daily_location' => '- Log in<br/>- Click the "Get Coins" gold button on top.<br/>- Click collect under "CLAIM FREE REWARDS"',
            'signup_bonus' => '1000 FC',
            'referral_bonus' => 'On referrals first purchase',
            'minimum_redemption' => '$75',
            'token_type' => 'FC',
            'token_amount_per_dollar' => 100,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => true,
            'usa_excluded' => ['MI', 'WA', 'ID'],
            'canada_excluded' => ['Ontario', 'Quebec'],
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Joker\'s Jewels Wild',
            'best_playthrough_game_url' => 'https://www.fortunecoins.com/play/50579/50579',
            'notes' => '',
            'terms_url' => 'https://www.fortunecoins.com/terms-and-conditions',
        ]);

        $media = $fortuneCoins->addMedia(public_path('img/casinos/fortune-coins.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $sportzino = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Sportzino',
            'slug' => 'sportzino',
            'url' => 'https://sportzino.com',
            'referral_url' => 'https://sportzino.com/signup/9535e2c1-8d6f-45b9-af1e-8d45023ce1ec',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => 1,
            'daily_bonus_reset_timing' => null,
            'daily_location' => '- Log in<br/>If your daily bonus is ready you will receive a pop-up.<br/>- Click the button<br/>- In the new pop-up scroll to "CLAIM FREE REWARDS" and hit collect on the "Progressive Daily Bonus" button.',
            'signup_bonus' => '7 SC',
            'referral_bonus' => 'On referrals first purchase',
            'minimum_redemption' => '$75',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => true,
            'usa_excluded' => ['MI', 'GA', 'WA', 'ID'],
            'canada_excluded' => null,
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Mine Field (board size: 6x15, bet 1SC, collect immediately) > Joker\'s Jewels Wild',
            'best_playthrough_game_url' => null,
            'notes' => 'Zula sister site offering casino and sweepstake sports betting. 1SC daily, good first deposit offer.',
            'terms_url' => 'https://sportzino.com/terms-and-conditions',
        ]);

        $media = $sportzino->addMedia(public_path('img/casinos/sportzino.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $crownCasino = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Crown Coins Casino',
            'slug' => 'crown-coins-casino',
            'url' => 'https://crowncoinscasino.com',
            'referral_url' => 'https://crowncoinscasino.com/?utm_campaign=5a92009f-aa9c-40d9-9b1a-1b08c838f75f&utm_source=friends',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .7,
            'daily_bonus_reset_timing' => null,
            'daily_location' => null,
            'signup_bonus' => '2 SC',
            'referral_bonus' => null,
            'minimum_redemption' => '$50',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => true,
            'usa_excluded' => ['ID', 'LA', 'MI', 'MT', 'NV', 'WA'],
            'canada_excluded' => null,
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Mine Field (board size: 6x15, bet 1SC, collect immediately) > Joker\'s Jewels Wild',
            'best_playthrough_game_url' => null,
            'notes' => '- Good selection of games including hacksaw + First purchase offer. Frequent discount packs.<br/>- Dailies increase with VIP level. GC play increases VIP level. Bronze is achievable with a few months worth of GC collected (and adds a $5 monthly bonus too!)',
            'terms_url' => 'https://crowncoinscasino.com/pages/terms-of-service',
        ]);

        $media = $crownCasino->addMedia(public_path('img/casinos/crown-casino.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $sweepSlots = SocialCasino::create([
            'active' => true,
            'tier' => 3,
            'name' => 'SweepSlots',
            'slug' => 'sweepslots',
            'url' => 'https://www.sweepslots.com',
            'referral_url' => 'https://www.sweepslots.com/invite/189617',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .35,
            'daily_bonus_reset_timing' => null,
            'daily_location' => 'Spin wheel at bottom center.',
            'signup_bonus' => '5 SC',
            'referral_bonus' => null,
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => ['DE', 'ID', 'KY', 'MI', 'NV', 'WA'],
            'canada_excluded' => null,
            'redemption_time' => '30-60 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Blackjack > Inner Fire',
            'best_playthrough_game_url' => '',
            'notes' => 'Company is in financial distress. Affiliates not being paid, 30 day redemption timeframe, lawsuits pending.',
            'terms_url' => 'https://sweepslots.com/p/terms-of-service/',
        ]);

        $media = $sweepSlots->addMedia(public_path('img/casinos/sweep-slots.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $stackr = SocialCasino::create([
            'active' => true,
            'tier' => 3,
            'name' => 'Stackr',
            'slug' => 'stackr',
            'url' => 'https://www.stackrcasino.com',
            'referral_url' => 'https://www.stackrcasino.com?referralcode=5ef330be-87b2-445d-bba8-42df90dfbbe5',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .1,
            'daily_bonus_reset_timing' => null,
            'daily_location' => 'Click "Spin the Wheel" in the left navigation menu.',
            'signup_bonus' => '5 SC',
            'referral_bonus' => 'None',
            'minimum_redemption' => '$100 Crypto',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => true,
            'usa_excluded' => ['MI', 'NV', 'WA', 'ID', 'DC'],
            'canada_excluded' => null,
            'redemption_time' => 'N/A',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Wilds of Fortune',
            'best_playthrough_game_url' => 'https://www.stackrcasino.com/game-play/475',
            'notes' => '- Used to be decent but has gotten worse and worse to the point where I no longer collect here. Wheel spin rarely pays out<br/>- You can get 1 free wheel spin daily, and can watch ads for more. I skip this site.',
            'terms_url' => 'https://www.stackrcasino.com/cms/terms-and-conditions',
        ]);

        $media = $stackr->addMedia(public_path('img/casinos/stackr.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $zoot = SocialCasino::create([
            'active' => true,
            'tier' => 3,
            'name' => 'Zoot',
            'slug' => 'zoot',
            'url' => 'https://getzoot.us',
            'referral_url' => 'https://getzoot.us/?referralCode=ZOOTwithUSER76857',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .5,
            'daily_bonus_reset_timing' => null,
            'daily_location' => 'Daily Reward in the top right of the navigation bar.',
            'signup_bonus' => '3 SC',
            'referral_bonus' => '10k GC for registration, 5 SC for purchase, refer 10 friends that purchase and get 100k GB and 50 SC for $2.99',
            'minimum_redemption' => '$40',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => true,
            'usa_excluded' => ['MI', 'NV', 'WA', 'ID', 'LA'],
            'canada_excluded' => ['Quebec'],
            'redemption_time' => 'UTO',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Any mines game (choose smallest # mines, only flip one, cashout, repeat)',
            'best_playthrough_game_url' => '',
            'notes' => 'Playthrough is 4x on purchase price and 20x on anything above (e.g. $10 for 50SC is $10 at 4x playthrough + $40 at 20x = $840 playthrough)',
            'terms_url' => 'https://getzoot.us/terms-of-use',
        ]);

        $media = $zoot->addMedia(public_path('img/casinos/zoot.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $spree = SocialCasino::create([
            'active' => true,
            'tier' => 3,
            'name' => 'Spree',
            'slug' => 'spree',
            'url' => 'https://spree.com',
            'referral_url' => 'https://spree.com/?r=787395',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .3,
            'daily_bonus_reset_timing' => null,
            'daily_location' => '"Claim Daily Coins" button in left navigation.',
            'signup_bonus' => '2.5 SC',
            'referral_bonus' => '10 SC',
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => ['AL', 'GA', 'ID', 'KY', 'MI', 'MT', 'NV', 'WA'],
            'canada_excluded' => null,
            'redemption_time' => '3.5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Live Baccarat > 888 Dragons',
            'best_playthrough_game_url' => '',
            'notes' => 'Good first purchase offers, average slot selection and gameplay. 0.20 SC daily. ',
            'terms_url' => 'https://spree.com/terms-of-service',
        ]);

        $media = $spree->addMedia(public_path('img/casinos/spree.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);
    }
}
