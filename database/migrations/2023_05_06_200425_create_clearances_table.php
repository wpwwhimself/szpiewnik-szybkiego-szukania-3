<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clearances', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("can")->nullable();
            $table->timestamps();
        });

        DB::table("clearances")->insert([
            [
                "name" => "praktykujący",
                "can" => "tworzyć zestawy, przeglądać pieśni",
            ],
            [
                "name" => "dewot",
                "can" => "tworzyć pieśni, tworzyć części stałe",
            ],
            [
                "name" => "fanatyk",
                "can" => "tworzyć formuły",
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clearances');
    }
}
