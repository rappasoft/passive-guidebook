<?php

use App\Models\SocialCasino;
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
        Schema::create('social_casino_news', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SocialCasino::class)->constrained()->onDelete('cascade');
            $table->string('title', 500);
            $table->string('url', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_casino_news');
    }
};
