<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Ordinarius;
use App\Models\OrdinariusColor;
use Illuminate\Http\Request;

class OrdinariusController extends Controller
{
    public function ordinarium(){
        $colors = OrdinariusColor::all();
        $ordinarium = [];
        foreach($colors as $color){
            $ordinarium[$color->name] = $color->ordinarium;
        }
        $ordinarium["*"] = Ordinarius::where("color_code", "*")->get();
        $ordinarium["events"] = Ordinarius::whereIn(
            "color_code",
            Formula::get("name")->toArray()
        )->get();

        return view("ordinarium", array_merge(
            ["title" => "Lista części stałych"],
            compact("ordinarium", "colors")
        ));
    }

    public function ordinarius($color_code, $part){
        $colors = OrdinariusColor::all();
        $ordinarius = Ordinarius::where("color_code", $color_code)
            ->where("part", $part)
            ->first();

        return view("ordinarius", array_merge(
            ["title" => "fdjsklafjkdsl"],
            compact("ordinarius", "colors")
        ));
    }

    public function ordinariusEdit(Request $rq){
        Ordinarius::where("color_code", $rq->color_code)
            ->where("part", $rq->part)
            ->first()
            ->update([
                "sheet_music" => $rq->sheet_music,
            ]);

        return redirect()->route("ordinarium")->with("success", "Część stała poprawiona");
    }
}
