<?php

use App\Models\SocialCasino;
use App\Models\SocialCasinoPromotion;
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
        Schema::create('social_casino_promotions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [SocialCasinoPromotion::TYPE_PROMOTION, SocialCasinoPromotion::TYPE_BONUS])->default(SocialCasinoPromotion::TYPE_PROMOTION);
            $table->foreignIdFor(SocialCasino::class)->constrained()->onDelete('cascade');
            $table->string('title', 500);
            $table->string('url', 500);
            $table->string('rewards');
            $table->string('rewards_label')->default('Prize');
            $table->float('dollar_value');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_casino_promotions');
    }
};
