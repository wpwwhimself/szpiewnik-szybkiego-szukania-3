<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("formula");
                $table->foreign("formula")->references("name")->on("formulas");
            $table->string("color");
                $table->foreign("color")->references("name")->on("ordinarius_colors");
            $table->string("sIntro")->nullable();
            $table->string("sOffer")->nullable();
            $table->string("sCommunion")->nullable();
            $table->string("sAdoration")->nullable();
            $table->string("sDismissal")->nullable();
            $table->text("pPsalm")->nullable();
            $table->text("pAccl")->nullable();
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
        Schema::dropIfExists('sets');
    }
}
