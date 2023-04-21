<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulaExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formula_extras', function (Blueprint $table) {
            $table->id();
            $table->string("formula");
                $table->foreign("formula")->references("name")->on("formulas");
            $table->string("name");
            $table->string("before");
            $table->boolean("replace")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formula_extras');
    }
}
