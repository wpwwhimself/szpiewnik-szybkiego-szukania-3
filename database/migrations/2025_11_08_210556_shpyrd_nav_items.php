<?php

use App\Models\Shipyard\NavItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ([
            [
                "name" => "Zestawy",
                "visible" => 2,
                "order" => 1,
                "icon" => model_icon("sets"),
                "target_type" => 1,
                "target_name" => "sets",
            ],
            [
                "name" => "Pieśni",
                "visible" => 2,
                "order" => 2,
                "icon" => model_icon("songs"),
                "target_type" => 1,
                "target_name" => "songs",
            ],
            [
                "name" => "Części stałe",
                "visible" => 2,
                "order" => 3,
                "icon" => model_icon("ordinariuses"),
                "target_type" => 1,
                "target_name" => "ordinarium",
            ],
            [
                "name" => "Miejsca",
                "visible" => 1,
                "order" => 4,
                "icon" => model_icon("places"),
                "target_type" => 1,
                "target_name" => "places",
            ],
            [
                "name" => "Formuły",
                "visible" => 1,
                "order" => 5,
                "icon" => model_icon("formulas"),
                "target_type" => 1,
                "target_name" => "formulas",
            ],
        ] as $data) {
            $nav_item = NavItem::create($data);

            if (in_array($data["name"], ["Miejsca", "Formuły"])) {
                $nav_item->roles()->sync([Str::singular($data["target_name"])."-manager"]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
