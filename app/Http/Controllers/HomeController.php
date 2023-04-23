<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Ordinarius;
use App\Models\OrdinariusColor;
use App\Models\SongCategory;
use App\Models\Set;
use App\Models\Song;
use Illuminate\Support\Str;
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
    public function setShow($song_id){
        $set = Set::findOrFail($song_id);

        return view("set", array_merge(
            ["title" => $set->name],
            compact("set")
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
    public function song($title_slug){
        $categories = SongCategory::all()->pluck("name", "id");
        $song = Song::all()->filter(function($song) use ($title_slug){
            return Str::slug($song->title) === $title_slug;
        })->first();

        //prefs
        $prefs = explode("/", $song->preferences);
        $prefs = array_combine([
          "sIntro",
          "sOffer",
          "sCommunion",
          "sAdoration",
          "sDismissal",
          "other"
        ], $prefs);

        return view("song", array_merge(
            ["title" => $song->title." | Edycja pieśni"],
            compact("song", "categories", "prefs")
        ));
    }
    public function songEdit(Request $rq){
        Song::where("title", $rq->title)->first()->update([
            "title" => $rq->title,
            "song_category_id" => $rq->song_category_id,
            "category_desc" => $rq->category_desc,
            "number_preis" => $rq->number_preis,
            "key" => $rq->key,
            "preferences" => implode("/", [
                intval($rq->has("sIntro")),
                intval($rq->has("sOffer")),
                intval($rq->has("sCommunion")),
                intval($rq->has("sAdoration")),
                intval($rq->has("sDismissal")),
                $rq->pref5 ?: "0"
            ]),
            "lyrics" => $rq->lyrics,
            "sheet_music" => $rq->sheet_music,
        ]);

        return redirect()->route("songs")->with("success", "Pieśń poprawiona");
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
