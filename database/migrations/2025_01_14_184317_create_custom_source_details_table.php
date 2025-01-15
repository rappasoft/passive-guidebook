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
        Schema::create('custom_source_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PassiveSourceUser::class)->constrained()->onDelete('cascade');
            $table->string('source');
            $table->decimal('amount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_source_details');
    }
};
