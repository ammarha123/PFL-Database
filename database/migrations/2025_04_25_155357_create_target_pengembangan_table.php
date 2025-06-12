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
        Schema::create('target_pengembangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapor_id')->constrained('rapor_perkembangan')->onDelete('cascade');
            $table->string('target');
            $table->string('kapan_tercapai')->nullable();
            $table->text('cara_mencapai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_pengembangan');
    }
};
