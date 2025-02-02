<?php

use App\Models\FreebieCategory;
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
        Schema::create('freebies', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->nullable();
            $table->boolean('active')->default(false);
            $table->string('name');
            $table->text('url')->nullable();
            $table->foreignIdFor(FreebieCategory::class)->nullable();
            $table->date('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freebies');
    }
};
