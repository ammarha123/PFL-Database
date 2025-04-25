<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('starting_11', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('datapertandingan')->onDelete('cascade');
            $table->string('player_name');
            $table->string('position'); // e.g., Goalkeeper, Forward
            $table->integer('x')->default(0); // X-coordinate for positioning on the field
            $table->integer('y')->default(0); // Y-coordinate for positioning on the field
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('starting_11');
    }
};

