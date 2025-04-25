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
        Schema::create('rapor_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapor_id')->constrained('rapor_perkembangan')->onDelete('cascade');
            $table->string('position_code'); // e.g., "LB", "CM", "LW"
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapor_positions');
    }
};
