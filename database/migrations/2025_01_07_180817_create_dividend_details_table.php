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
        Schema::create('dividend_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\PassiveSourceUser::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Security::class);
            $table->decimal('cost_basis');
            $table->decimal('quantity');
            $table->decimal('institution_price');
            $table->date('institution_price_as_of')->nullable();
            $table->decimal('institution_value');
            $table->decimal('yield_on_cost')->default(0);
            $table->decimal('annual_income')->default(0);
            $table->boolean('update_dividend_automatically')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dividend_details');
    }
};
