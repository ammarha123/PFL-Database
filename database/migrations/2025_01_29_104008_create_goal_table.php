<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('datapertandingan')->onDelete('cascade');
            $table->string('player');
            $table->integer('minute');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal');
    }
};

