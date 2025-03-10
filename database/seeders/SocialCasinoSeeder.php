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
            'daily_bonus_reset_timing' => 'Once a day. Resets overnight.',
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
            'daily_bonus_reset_timing' => 'Once a day. Resets overnight.',
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
            'daily_bonus_reset_timing' => 'Once a day. Resets overnight.',
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
            'daily_bonus_reset_timing' => '24 hours since last redemption.',
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

        $wowVegas = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Wow Vegas',
            'slug' => 'wow-vegas',
            'url' => 'https://www.wowvegas.com',
            'referral_url' => 'https://www.wowvegas.com/?raf=7145855',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .3,
            'daily_bonus_reset_timing' => 'Once a day. Resets overnight.',
            'daily_location' => null,
            'signup_bonus' => '4.5 SC',
            'referral_bonus' => '5k WOW & 30 SC',
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => true,
            'usa_excluded' => ['ID', 'NV', 'WA'],
            'canada_excluded' => ['Quebec'],
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'AutoRoulette > 888 Dragon / Wilds of Fortune',
            'best_playthrough_game_url' => '',
            'notes' => '" - Daily $ scales with VIP level.<br/>
 - Free spins are dropped daily in the early morning and evening<br/>
 - Find them in the WowVegas chat or get alerts via our discord.<br/>
 - Free to enter bingo for up to 10SC every 30min.<br/>
 - Great happy hours 5 days per week."',
            'terms_url' => 'https://www.wowvegas.com/terms-and-conditions',
        ]);

        $media = $wowVegas->addMedia(public_path('img/casinos/wow-vegas.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $realprize = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Real Prize',
            'slug' => 'real-prize',
            'url' => 'https://www.realprize.com',
            'referral_url' => 'https://www.realprize.com/refer/617863',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .3,
            'daily_bonus_reset_timing' => 'Once a day. Resets overnight.',
            'daily_location' => null,
            'signup_bonus' => '2 SC',
            'referral_bonus' => '200k GC & 70 SC',
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => null,
            'canada_excluded' => null,
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Joker\'s Jewels Wild',
            'best_playthrough_game_url' => 'https://realprize.com/#!c/Slots/Jokers%20Jewels%20Wild/21304956/real',
            'notes' => '0.30 SC daily - Good package deals, good slot selection, first deposit offer + 2.2 SC free',
            'terms_url' => 'https://realprize.com/p/terms-of-use-service-agreement/',
        ]);

        $media = $realprize->addMedia(public_path('img/casinos/real-prize.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $pulsz = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Pulsz',
            'slug' => 'pulsz',
            'url' => 'http://www.pulsz.com',
            'referral_url' => 'http://www.pulsz.com/?invited_by=1qwp7n',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .8,
            'daily_bonus_reset_timing' => '24 hours since last redemption.',
            'daily_location' => null,
            'signup_bonus' => null,
            'referral_bonus' => '8k GC & 40 SC',
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => ['MI', 'NV', 'WA', 'ID', 'MT', 'AL', 'TN'],
            'canada_excluded' => null,
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Blackjack',
            'best_playthrough_game_url' => 'https://www.pulsz.com/table-games/multihand-blackjack/sweepstake/play?category=&gameIndex=',
            'notes' => 'Fast deposit and withdraws - Daily progressive reward (Starts at 30c)',
            'terms_url' => 'https://www.pulsz.com/terms-of-use',
        ]);

        $media = $pulsz->addMedia(public_path('img/casinos/pulsz.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $pulszBingo = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Pulsz Bingo',
            'slug' => 'pulsz-bingo',
            'url' => 'http://www.pulszbingo.com',
            'referral_url' => 'http://www.pulszbingo.com/?invited_by=v914ej',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .8,
            'daily_bonus_reset_timing' => '24 hours since last redemption.',
            'daily_location' => 'If it\'s ready a "Wheel of Winners" will pop up.',
            'signup_bonus' => '2 SC',
            'referral_bonus' => '6k GC & 30 SC',
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => ['WA', 'ID', 'MI', 'NV', 'MT', 'AL', 'TN'],
            'canada_excluded' => null,
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Blackjack',
            'best_playthrough_game_url' => 'https://www.pulszbingo.com/table-games/multihand-blackjack/sweepstake/play?category=&gameIndex=',
            'notes' => 'Daily login wheel spin. Best Bingo / Free bingo cards often via email',
            'terms_url' => 'https://www.pulszbingo.com/terms-of-use',
        ]);

        $media = $pulszBingo->addMedia(public_path('img/casinos/pulsz-bingo.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $modo = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Modo',
            'slug' => 'modo',
            'url' => 'https://modo.us',
            'referral_url' => 'https://modo.us?referralCode=USuYo_WTXl_O',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => 1,
            'daily_bonus_reset_timing' => '24 hours since last redemption.',
            'daily_location' => 'Bottom of left navigation.',
            'signup_bonus' => '1 SC',
            'referral_bonus' => '100k GC & 15 SC',
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => ['WA', 'MT', 'MD', 'PA', 'NJ', 'CT', 'WV', 'LA', 'RI', 'DE', 'NV', 'MI', 'ID'],
            'canada_excluded' => null,
            'redemption_time' => '3-7 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Blackjack',
            'best_playthrough_game_url' => 'https://modo.us/play/blackjack-lucky-sevens',
            'notes' => 'Great welcome bonus offers. Good slot selection from multiple providers including Hacksaw',
            'terms_url' => 'https://modo.us/terms-and-conditions.pdf',
        ]);

        $media = $modo->addMedia(public_path('img/casinos/modo.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $moozi = SocialCasino::create([
            'active' => true,
            'tier' => 3,
            'name' => 'Moozi',
            'slug' => 'moozi',
            'url' => 'https://moozi.com',
            'referral_url' => 'https://moozi.com/signup?referral_code=9138335352',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .5,
            'daily_bonus_reset_timing' => null,
            'daily_location' => 'Left side navigation.',
            'signup_bonus' => '2 SC',
            'referral_bonus' => '300k GC & 30 SC',
            'minimum_redemption' => '$75',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => null,
            'canada_excluded' => null,
            'redemption_time' => '',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Top Card Trumps',
            'best_playthrough_game_url' => 'https://moozi.com/play/bs_48/45/Njk5NzY4/silver',
            'notes' => '',
            'terms_url' => 'https://moozi.com/terms-of-use',
        ]);

        $media = $moozi->addMedia(public_path('img/casinos/moozi.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $chumba = SocialCasino::create([
            'active' => true,
            'tier' => 1,
            'name' => 'Chumba',
            'slug' => 'chumba',
            'url' => 'https://lobby.chumbacasino.com/',
            'referral_url' => null,
            'sweeps_extension_eligible' => true,
            'daily_bonus' => 1,
            'daily_bonus_reset_timing' => 'Once a day. Resets overnight.',
            'daily_location' => '- Click "Get Coins" on top<br/>- Switch to 3rd tab "Daily Bonus"<br/>- Click CLAIM',
            'signup_bonus' => '2 SC',
            'referral_bonus' => null,
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => true,
            'usa_excluded' => ['CT', 'ID', 'MI', 'MT', 'WA'],
            'canada_excluded' => ['Quebec'],
            'redemption_time' => '3-5 days',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Blackjack',
            'best_playthrough_game_url' => 'https://lobby.chumbacasino.com/games/blackJack',
            'notes' => '1 SC Daily - One of the origional sweepstake casino site. Classic site with all house games',
            'terms_url' => 'https://www.chumbacasino.com/documents/240916-POL-CHU-T&Cs-10.0.pdf',
        ]);

        $media = $chumba->addMedia(public_path('img/casinos/chumba.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $chanced = SocialCasino::create([
            'active' => true,
            'tier' => 2,
            'name' => 'Chanced',
            'slug' => 'chanced',
            'url' => 'https://chanced.com',
            'referral_url' => 'https://chanced.com/c/2zppvl',
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .5,
            'daily_bonus_reset_timing' => '24 hours since last redemption.',
            'daily_location' => '- Click "Wallet" on top<br/>- Switch to 3rd tab "Daily Bonus"<br/>- Click "Claim Daily Bonus" at the bottom.',
            'signup_bonus' => null,
            'referral_bonus' => '100k GC & 15 SC',
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => ['MI', 'NV', 'WA', 'ID', 'KY'],
            'canada_excluded' => null,
            'redemption_time' => 'Instant',
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Joker\'s Jewels Wild',
            'best_playthrough_game_url' => 'https://chanced.com/pragmatic/casino/games/vs5jjwild',
            'notes' => '3x playthrough on all deposits - Claim a small SC bonus each hour + Livestream codes',
            'terms_url' => 'https://chanced.com/docs/Terms-And-Conditions.pdf',
        ]);

        $media = $chanced->addMedia(public_path('img/casinos/chanced.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

        $rollingriches = SocialCasino::create([
            'active' => true,
            'tier' => 2,
            'name' => 'Rolling Riches',
            'slug' => 'rolling-riches',
            'url' => 'https://www.rollingriches.com',
            'referral_url' => null,
            'sweeps_extension_eligible' => true,
            'daily_bonus' => .8,
            'daily_bonus_reset_timing' => '6 hours since last redemption.',
            'daily_location' => '- Click "GET COINS" on the left navigation<br/>- Click the "BONUSES" tab',
            'signup_bonus' => '5 SC',
            'referral_bonus' => null,
            'minimum_redemption' => '$100',
            'token_type' => 'SC',
            'token_amount_per_dollar' => 1,
            'real_money_payout' => true,
            'usa_allowed' => true,
            'canada_allowed' => false,
            'usa_excluded' => ['CT', 'DE', 'HI', 'ID', 'KY', 'MI', 'MT', 'NV', 'WA'],
            'canada_excluded' => null,
            'redemption_time' => null,
            'must_play_before_redemption' => true,
            'best_playthrough_game' => 'Gravity Blackjack > Joker\'s Jewels Wild',
            'best_playthrough_game_url' => null,
            'notes' => '$0.20 every 6 hours',
            'terms_url' => 'https://s3.us-west-1.amazonaws.com/aws.rollingriches.com/rollingriches/PDFs/Rolling+Riches+Terms+and+Conditions+FINAL+1-8.pdf',
        ]);

        $media = $rollingriches->addMedia(public_path('img/casinos/rolling-riches.png'))
            ->preservingOriginal()
            ->toMediaCollection('logo');
        $media->update(['uuid' => Uuid::uuid4()]);

    }
}
