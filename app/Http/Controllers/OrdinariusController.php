<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Ordinarius;
use App\Models\OrdinariusColor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        return view("ordinarium.list", array_merge(
            ["title" => "Lista mszy"],
            compact("ordinarium", "colors")
        ));
    }

    public function ordinarius($color_code, $part){
        $color = OrdinariusColor::find($color_code);
        $ordinarius = Ordinarius::where("part", $part)
            ->get()
            ->filter(fn($ord) => (Str::slug($ord->color_code) ?: "*") == $color_code)
            ->first()
            ;

        return view("ordinarium.edit", array_merge(
            ["title" => implode(" ", [
                ucfirst($part),
                ($color ? "($color->display_name)" : ""),
                ($ordinarius->isSpecial ? "($ordinarius->color_code)" : ""),
                "| Edycja cz. st."
            ])],
            compact("ordinarius")
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
