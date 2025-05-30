<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('datates', function (Blueprint $table) {
            $table->date('tanggal')->after('category')->nullable(); // Allow nullable for existing data
        });
    }

    public function down()
    {
        Schema::table('datates', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
};

