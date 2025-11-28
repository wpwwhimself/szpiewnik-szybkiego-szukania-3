<?php

use App\Models\OrdinariusColor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ordinarius_colors', function (Blueprint $table) {
            $current_ordering = OrdinariusColor::all()->pluck("ordering", "name");
            $current_color = "green"; $next_color = null; $i = 1;
            while ($current_color != null) {
                $next_color = $current_ordering[$current_color];
                $current_ordering[$current_color] = $i++;
                $current_color = $next_color;
            }

            DB::table("ordinarius_colors")->update(["ordering" => null]);

            $table->integer("ordering")->nullable()->change();

            foreach ($current_ordering as $color_name => $index) {
                OrdinariusColor::find($color_name)->update(["ordering" => $index]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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
};
