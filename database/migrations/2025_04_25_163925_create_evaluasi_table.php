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
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapor_id')->constrained('rapor_perkembangan')->onDelete('cascade');
            $table->text('positif_attacking')->nullable();
            $table->text('negatif_attacking')->nullable();
            $table->text('positif_defending')->nullable();
            $table->text('negatif_defending')->nullable();
            $table->text('positif_transisi')->nullable();
            $table->text('negatif_transisi')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi');
    }
};
