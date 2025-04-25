<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('datavideos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id')->nullable(); // Reference to match
            $table->unsignedBigInteger('latihan_id')->nullable(); // Reference to latihan
            $table->string('video_category'); // Full Match, Analisa Tim, Analisa Individual
            $table->string('link_video'); // Video link
            $table->text('description')->nullable(); // Description field
            $table->timestamps();

            // Foreign keys if related tables exist
            // $table->foreign('match_id')->references('id')->on('datapertandingan')->onDelete('set null');
            // $table->foreign('latihan_id')->references('id')->on('latihan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('datavideos');
    }
};
