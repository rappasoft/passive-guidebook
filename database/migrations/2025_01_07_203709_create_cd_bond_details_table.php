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
        Schema::create('cd_bond_details', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['CD', 'Bond'])->default('CD');
            $table->foreignIdFor(\App\Models\PassiveSourceUser::class)->constrained()->onDelete('cascade');
            $table->string('bank_name');
            $table->decimal('apy');
            $table->decimal('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cd_bond_details');
    }
};
