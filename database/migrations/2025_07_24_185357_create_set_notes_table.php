<?php

use App\Models\Clearance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("set_id")->constrained();
            $table->foreignId("user_id")->constrained();
            $table->string("element_code");
            $table->text("content");
            $table->timestamps();
        });

        Clearance::find(1)->update([
            "can" => "tworzyć zestawy (i notatki w tychże) i miejsca",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('set_notes');

        Clearance::find(1)->update([
            "can" => "tworzyć zestawy i miejsca",
        ]);
    }
}
