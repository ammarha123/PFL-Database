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
        Schema::create('datalatihan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('latihan_file_path');
            $table->string('player_category')->nullable(); // Senior, Junior, Youth
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datalatihan');
    }
};
