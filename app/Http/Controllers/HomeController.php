<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Ordinarius;
use App\Models\OrdinariusColor;
use App\Models\SongCategory;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function sets(){
        $formulas = Formula::all();
        $sets = [];
        foreach($formulas as $formula){
            $sets[$formula->name] = $formula->sets;
        }

        return view("sets", array_merge(
            ["title" => "Dostępne zestawy"],
            compact("formulas", "sets")
        ));
    }

    public function songs(){
        $categories = SongCategory::all();
        $songs = [];
        foreach($categories as $cat){
            $songs[$cat->name] = $cat->songs;
        }

        return view("songs", array_merge(
            ["title" => "Lista pieśni"],
            compact("songs", "categories")
        ));
    }

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

    public function formulas(){
        return view("ordinarium", ["title" => "Lista formuł"]);
    }

    public function places(){
        return view("places", ["title" => "Lista miejsc"]);
    }
}
