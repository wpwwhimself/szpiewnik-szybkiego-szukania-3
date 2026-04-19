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
        Schema::table('ordinarius_colors', function (Blueprint $table) {
            $table->integer("group")->comment("0 = zwykłe, 1 = melancholijne, 2 = świąteczne")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordinarius_colors', function (Blueprint $table) {
            $table->dropColumn("group");
        });
    }
};
