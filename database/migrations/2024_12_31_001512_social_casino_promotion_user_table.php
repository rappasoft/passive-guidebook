<?php

use App\Models\SocialCasinoPromotion;
use App\Models\User;
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
        Schema::create('social_casino_promotion_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SocialCasinoPromotion::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class);
            $table->unique(['social_casino_promotion_id', 'user_id'], 'social_casino_promotion_id_user_id_unique');
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_casino_promotion_user');
    }
};
