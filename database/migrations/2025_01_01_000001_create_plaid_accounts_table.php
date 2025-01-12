<?php

use App\Models\PlaidToken;
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
        Schema::create('plaid_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PlaidToken::class)->constrained()->onDelete('cascade');
            $table->string('account_id')->unique();
            $table->string('name');
            $table->string('mask');
            $table->string('subtype');
            $table->decimal('balance', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plaid_accounts');
    }
};
