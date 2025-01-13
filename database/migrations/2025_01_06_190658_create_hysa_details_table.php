<?php

use App\Models\PassiveSourceUser;
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
        Schema::create('hysa_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PassiveSourceUser::class)->constrained()->onDelete('cascade');
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->decimal('apy')->default(0);
            $table->decimal('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hysa_details');
    }
};
