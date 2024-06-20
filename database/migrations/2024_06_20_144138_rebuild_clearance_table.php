<?php

use App\Models\Clearance;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RebuildClearanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Clearance::find(1)->update([
            "name" => "praktykujący",
            "can" => "tworzyć zestawy i miejsca",
        ]);
        Clearance::find(2)->update([
            "name" => "dewot",
            "can" => "tworzyć pieśni i części stałe",
        ]);
        Clearance::find(3)->update([
            "name" => "fanatyk",
            "can" => "tworzyć formuły",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Clearance::find(1)->update([
            "name" => "praktykujący",
            "can" => "przeglądać wszystko, tworzyć zestawy i miejsca",
        ]);
        Clearance::find(2)->update([
            "name" => "dewot",
            "can" => "tworzyć pieśni i części stałe",
        ]);
        Clearance::find(3)->update([
            "name" => "fanatyk",
            "can" => "tworzyć formuły",
        ]);
    }
}
