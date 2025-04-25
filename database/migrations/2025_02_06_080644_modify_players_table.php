<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::table('players', function (Blueprint $table) {
            // Remove 'category' column
            $table->dropColumn('category');

            // Add new fields
            $table->string('photo_profile')->nullable()->after('position'); // Store profile photo URL
        });
    }

    public function down(): void {
        Schema::table('players', function (Blueprint $table) {
            $table->enum('category', ['Senior', 'Junior', 'Youth'])->after('bod');
            $table->dropColumn('photo_profile');
        });
    }
};

