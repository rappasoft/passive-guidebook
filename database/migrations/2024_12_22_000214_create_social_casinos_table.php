<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social_casinos', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tier')->default(1);
            $table->boolean('active')->default(true);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('url', 500);
            $table->string('referral_url')->nullable();
            $table->boolean('sweeps_extension_eligible')->default(false);
            $table->decimal('daily_bonus')->nullable();
            $table->string('daily_bonus_reset_timing')->nullable();
            $table->string('daily_location', 500)->nullable();
            $table->string('signup_bonus', 500)->nullable();
            $table->string('referral_bonus', 500)->nullable();
            $table->string('minimum_redemption')->default(100);
            $table->string('token_type')->default('SC');
            $table->smallInteger('token_amount_per_dollar')->default(1);
            $table->boolean('real_money_payout')->default(true);
            $table->boolean('usa_allowed')->default(true);
            $table->boolean('canada_allowed')->default(true);
            $table->json('usa_excluded')->nullable();
            $table->json('canada_excluded')->nullable();
            $table->string('redemption_time')->nullable();
            $table->boolean('must_play_before_redemption')->default(true);
            $table->string('best_playthrough_game')->nullable();
            $table->string('best_playthrough_game_url', 500)->nullable();
            $table->string('terms_url', 500)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_casinos');
    }
};
