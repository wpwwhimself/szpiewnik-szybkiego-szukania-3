<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->string("title")->primary();
            $table->foreignId("song_category_id")->constrained("song_categories");
            $table->string("category_desc")->nullable();
            $table->string("number_preis")->nullable();
            $table->string("key")->nullable();
            $table->string("preferences")->default("0/0/0/0/0/0");
            $table->text("lyrics")->nullable();
            $table->text("sheet_music")->nullable();
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
        Schema::dropIfExists('songs');
    }
}
