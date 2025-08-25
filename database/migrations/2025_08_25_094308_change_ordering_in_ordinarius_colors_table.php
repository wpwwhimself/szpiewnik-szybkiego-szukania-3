<?php

use App\Models\OrdinariusColor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeOrderingInOrdinariusColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordinarius_colors', function (Blueprint $table) {
            $current_ordering = OrdinariusColor::orderBy("ordering")->get()->pluck("name");
            DB::table("ordinarius_colors")->update(["ordering" => null]);

            $table->string("ordering")->nullable()->change();
            $table->foreign("ordering")->references("name")->on("ordinarius_colors");

            foreach ($current_ordering as $key => $color_name) {
                if (!isset($current_ordering[$key + 1])) {
                    continue;
                }
                OrdinariusColor::find($color_name)->update(["ordering" => $current_ordering[$key + 1]]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordinarius_colors', function (Blueprint $table) {
            OrdinariusColor::update(["ordering" => null]);

            $table->dropForeign("ordinarius_colors_ordering_foreign");
            $table->integer("ordering")->nullable()->change();
        });
    }
}
