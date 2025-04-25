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
        Schema::table('datates', function (Blueprint $table) {
            if (!Schema::hasColumn('datates', 'fat_chest')) {
                $table->float('fat_chest')->nullable()->after('bmi');
            }
            if (!Schema::hasColumn('datates', 'fat_thighs')) {
                $table->float('fat_thighs')->nullable()->after('bmi');
            }
            if (!Schema::hasColumn('datates', 'fat_abs')) {
                $table->float('fat_abs')->nullable()->after('bmi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datates', function (Blueprint $table) {
            if (Schema::hasColumn('datates', 'fat_chest')) {
                $table->dropColumn('fat_chest');
            }
            if (Schema::hasColumn('datates', 'fat_thighs')) {
                $table->dropColumn('fat_thighs');
            }
            if (Schema::hasColumn('datates', 'fat_abs')) {
                $table->dropColumn('fat_abs');
            }
            if (Schema::hasColumn('datates', 'muscle_mass')) {
                $table->dropColumn('muscle_mass');
            }
            if (Schema::hasColumn('datates', 'arm_span')) {
                $table->dropColumn('arm_span');
            }
            if (Schema::hasColumn('datates', 'wing_span')) {
                $table->dropColumn('wing_span');
            }
        });
    }
};
