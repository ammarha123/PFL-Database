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
            $table->float('fat_percentage')->nullable();
            $table->float('muscle_mass')->nullable();
            $table->float('arm_span')->nullable();
            $table->float('wing_span')->nullable();
            
            // ✅ FMS (Scores 0-3 per test)
            $table->integer('fms_squat')->nullable();
            $table->integer('fms_hurdle')->nullable();
            $table->integer('fms_lunge')->nullable();
            $table->integer('fms_shoulder')->nullable();
            $table->integer('fms_leg_raise')->nullable();
            $table->integer('fms_push_up')->nullable();
            $table->integer('fms_rotary')->nullable();
            $table->integer('fms_total')->nullable(); 
        
            // ✅ VO2max & MAS
            $table->float('vo2max_type')->nullable();
            $table->float('vo2max_duration')->nullable();
            $table->float('speed')->nullable();
            $table->float('oxygen')->nullable();
            $table->float('vo2max_score')->nullable();


            $table->float('mas_type')->nullable(); // km/h
            $table->float('mas_speed')->nullable();
            $table->float('mas_duration')->nullable(); // km/h
            $table->float('mas_distance')->nullable(); // km/h
        
            $table->timestamps(); // Created and updated timestamps

            // ✅ Foreign Key Constraint
            // $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datates'); // Drops the table if it exists
    }
};
