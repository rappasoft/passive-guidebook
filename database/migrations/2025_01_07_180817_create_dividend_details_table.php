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
            $table->string('ticker');
            $table->decimal('yield');
            $table->decimal('amount');
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
