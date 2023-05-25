<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeExtrasBeforeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formula_extras', function (Blueprint $table) {
            $table->string("before")->nullable()->change();
        });
        Schema::table('set_extras', function (Blueprint $table) {
            $table->string("before")->nullable()->change();
        });
        Schema::table('place_extras', function (Blueprint $table) {
            $table->string("before")->nullable()->change();
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
            $table->string("before")->change();
        });
        Schema::table('set_extras', function (Blueprint $table) {
            $table->string("before")->change();
        });
        Schema::table('place_extras', function (Blueprint $table) {
            $table->string("before")->change();
        });
    }
}
