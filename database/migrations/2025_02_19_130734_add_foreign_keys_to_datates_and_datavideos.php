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
        // Adding foreign key to `datates`
        Schema::table('datates', function (Blueprint $table) {
            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Adding foreign keys to `datavideos`
        Schema::table('datavideos', function (Blueprint $table) {
            $table->foreign('match_id')
                ->references('id')->on('datapertandingan')
                ->onDelete('set null');

            $table->foreign('latihan_id')
                ->references('id')->on('datalatihan')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('datates', function (Blueprint $table) {
            $table->dropForeign(['player_id']);
        });

        Schema::table('datavideos', function (Blueprint $table) {
            $table->dropForeign(['match_id']);
            $table->dropForeign(['latihan_id']);
        });
    }
};
