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
        Schema::create('securities', function (Blueprint $table) {
            $table->id();
            $table->string('plaid_security_id');
            $table->string('type')->nullable();
            $table->string('sector')->nullable();
            $table->string('industry')->nullable();
            $table->string('name');
            $table->string('symbol');
            $table->decimal('close_price')->nullable();
            $table->date('close_price_as_of')->nullable();
            $table->decimal('dividend_yield')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('securities');
    }
};
