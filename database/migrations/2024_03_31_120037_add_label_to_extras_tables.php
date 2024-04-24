<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabelToExtrasTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formula_extras', function (Blueprint $table) {
            $table->string("label")->nullable()->after("name");
        });
        Schema::table('place_extras', function (Blueprint $table) {
            $table->string("label")->nullable()->after("name");
        });
        Schema::table('set_extras', function (Blueprint $table) {
            $table->string("label")->nullable()->after("name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formula_extras', function (Blueprint $table) {
            $table->dropColumn("label");
        });
        Schema::table('place_extras', function (Blueprint $table) {
            $table->dropColumn("label");
        });
        Schema::table('set_extras', function (Blueprint $table) {
            $table->dropColumn("label");
        });
    }
}
