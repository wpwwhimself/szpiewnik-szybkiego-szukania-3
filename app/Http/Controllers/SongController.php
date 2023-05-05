<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\SongCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SongController extends Controller
{
    public function songs(){
        $categories = SongCategory::all();
        $songs = [];
        foreach($categories as $cat){
            $songs[$cat->name] = $cat->songs;
        }

        return view("songs.list", array_merge(
            ["title" => "Lista pieśni"],
            compact("songs", "categories")
        ));
    }

    public function song($title_slug){
        $categories = SongCategory::all()->pluck("name", "id");
        $song = Song::all()->filter(function($song) use ($title_slug){
            return Str::slug($song->title) === $title_slug;
        })->first();
        if(!$song) return abort(404);

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

        return view("songs.edit", array_merge(
            ["title" => $song->title." | Edycja pieśni"],
            compact("song", "categories", "prefs")
        ));
    }

    public function songEdit(Request $rq){
        if($rq->action === "update"){
            Song::where("title", $rq->old_title)->update([
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
            $response = "Pieśń poprawiona";
        }else{
            Song::where("title", $rq->title)->delete();
            $response = "Pieśń usunięta";
        }

        return redirect()->route("songs")->with("success", $response);
    }

    public function songAdd(){
        $new_song_title = "--Nowa pieśń--";
        if(!Song::where("title", $new_song_title)->count()) Song::insert([
            "title" => $new_song_title,
            "song_category_id" => 1,
        ]);

        return redirect()->route("song", ["title_slug" => Str::slug($new_song_title)])->with("success", "Szablon utworzony, dodaj pieśń");
    }
}
