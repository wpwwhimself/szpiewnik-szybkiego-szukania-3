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
        $colors = OrdinariusColor::orderByRaw("case when ordering is null then 99 else ordering end")->get();
        $ordinarium = [];
        foreach($colors as $color){
            $ordinarium[$color->name] = $color->ordinarium;
        }
        $ordinarium["*"] = Ordinarius::where("color_code", "*")
            ->orderByRaw(
                "case
                    when part regexp '^kyrie' then 1
                    when part regexp '^gloria' then 2
                    when part regexp '^psalm' then 3
                    when part regexp '^aklamacja' then 4
                    when part regexp '^sanctus' then 5
                    when part regexp '^agnus-dei' then 6
                else 99 end"
            )
            ->get();
        $ordinarium["events"] = Ordinarius::whereIn(
            "color_code",
            Formula::get("name")->toArray()
        )
            ->orderByRaw(
                "case
                    when part regexp '^kyrie' then 1
                    when part regexp '^gloria' then 2
                    when part regexp '^psalm' then 3
                    when part regexp '^aklamacja' then 4
                    when part regexp '^sanctus' then 5
                    when part regexp '^agnus-dei' then 6
                else 99 end"
            )
            ->get();

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

    public function ordinariusPresent($color_code){
        $ordinarium = Ordinarius::where("color_code", $color_code)->get();
        $color = OrdinariusColor::find($color_code);
        if(!$ordinarium) return abort(404);

        return view("ordinarium.present", array_merge(
            ["title" => "Części stałe: ".$color->display_name],
            compact("ordinarium", "color")
        ));
    }

    public function ordinariusEdit(Request $rq){
        Ordinarius::where("color_code", $rq->color_code)
            ->where("part", $rq->part)
            ->first()
            ->update([
                "sheet_music" => implode(Ordinarius::$VAR_SEP, $rq->sheet_music),
            ]);

        return redirect()->route("ordinarium")->with("success", "Część stała poprawiona");
    }
}
