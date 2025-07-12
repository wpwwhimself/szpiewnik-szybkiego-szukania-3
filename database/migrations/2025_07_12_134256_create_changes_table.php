<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changes', function (Blueprint $table) {
            $table->id();
            $table->timestamp("date")->useCurrent();
            $table->foreignId("user_id")->constrained();
            $table->string("changeable_type");
            $table->string("changeable_id");
            $table->string("action");
            $table->json("details")->nullable();
        });

        DB::table("clearances")->insert([
            "id" => 4,
            "name" => "awatar",
            "can" => "przeglądać operacje innych użytkowników",
        ]);

        User::find(1)->update(["clearance_id" => 4]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::find(1)->update(["clearance_id" => 3]);
        DB::table("clearances")->where("id", 4)->delete();
        Schema::dropIfExists('changes');
    }
}
