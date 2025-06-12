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
        Schema::create('datates', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->unsignedBigInteger('player_id'); // Player name
            $table->string('category'); // Test category (Antropometri, FMS, etc.)
            
            // ✅ Anthropometry Data
            $table->float('weight')->nullable(); // kg
            $table->float('height')->nullable(); // cm
            $table->float('bmi')->nullable();
            $table->float('fat_chest')->nullable();
            $table->float('fat_thighs')->nullable();
            $table->float('fat_abs')->nullable();
            $table->float('fat_percentage')->nullable();
            // ✅ VO2max & MAS
            $table->float('vo2max_level')->nullable();
            $table->float('vo2max_balikan')->nullable();
        
            $table->timestamps(); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datates');
    }
};
