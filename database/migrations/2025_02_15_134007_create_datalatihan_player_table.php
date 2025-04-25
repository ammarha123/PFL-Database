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
        Schema::create('datalatihan_player', function (Blueprint $table) {
            $table->id();
            $table->foreignId('datalatihan_id')->constrained('datalatihan')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Tidak Hadir']);
            $table->text('reason')->nullable(); // Reason is only required if status = "Tidak Hadir"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datalatihan_player');
    }
};

