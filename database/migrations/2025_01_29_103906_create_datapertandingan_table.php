<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('datapertandingan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('home_team');
            $table->string('away_team');
            $table->integer('home_score');
            $table->integer('away_score');
            $table->string('location');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datapertandingan');
    }
};

