<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_extras', function (Blueprint $table) {
            $table->id();
            $table->string("place");
                $table->foreign("place")->references("name")->on("places");
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
        Schema::dropIfExists('place_extras');
    }
}
