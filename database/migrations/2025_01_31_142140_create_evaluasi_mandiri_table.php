<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evaluasi_mandiri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Connect to users table
            $table->date('tanggal');
            $table->text('positif_attacking')->nullable();
            $table->text('negatif_attacking')->nullable();
            $table->text('positif_defending')->nullable();
            $table->text('negatif_defending')->nullable();
            $table->text('target_pengembangan_1')->nullable();
            $table->text('target_pengembangan_2')->nullable();
            $table->text('target_pengembangan_3')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_mandiri');
    }
};
