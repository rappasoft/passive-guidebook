<?php

use App\Models\SocialCasino;
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
        Schema::create('social_casino_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(SocialCasino::class)->constrained()->onDelete('cascade');
            $table->boolean('is_using')->default(true);
            $table->boolean('hide_redeemed_promotions')->default(false);
            $table->boolean('hide_redeemed_bonuses')->default(false);
            $table->boolean('hide_expired_promotions')->default(false);
            $table->boolean('hide_expired_bonuses')->default(false);
            $table->boolean('notify_new_promotions')->default(false);
            $table->boolean('notify_new_bonuses')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_casino_user');
    }
};
