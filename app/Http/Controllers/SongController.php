<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\SongCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleXMLElement;

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

    public function songPresent($title_slug){
        $song = Song::all()->filter(function($song) use ($title_slug){
            return Str::slug($song->title) === $title_slug;
        })->first();
        if(!$song) return abort(404);

        return view("songs.present", array_merge(
            ["title" => $song->title],
            compact("song")
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
                "lyrics" => implode(Song::$VAR_SEP, $rq->lyrics),
                "sheet_music" => implode(Song::$VAR_SEP, $rq->sheet_music),
            ]);
            $response = "Pieśń poprawiona";
        }else{
            Song::where("title", $rq->old_title)->delete();
            $response = "Pieśń usunięta";
        }

        return redirect()->route("songs")->with("success", $response);
    }

    public function songAdd(){
        if(Auth::user()?->clearance->id < 2) return back()->with("error", "Nie masz uprawnień do utworzenia pieśni");

        $new_song_title = "--Nowa pieśń--";
        if(!Song::where("title", $new_song_title)->count()) Song::insert([
            "title" => $new_song_title,
            "song_category_id" => 1,
        ]);

        return redirect()->route("song", ["title_slug" => Str::slug($new_song_title)])->with("success", "Szablon utworzony, dodaj pieśń");
    }

    public function songAutocomplete(Request $rq){
        $initial = substr($rq->title, 0, 1);
        $mass_order = collect(json_decode((new DataModController)->massOrder()->content()))
            ->pluck("value");
        if((strlen($rq->title) >= 3)){
            $titles = !in_array($initial, ["x", "!"])
                ? Song::where("title", "like", "%$rq->title%")->pluck("title")
                : $mass_order
                    ->filter(fn($el) => preg_match("/$rq->title/i", $el))
                    ->values();
        }else $titles = [];
        return response()->json($titles);
    }

    public function songRandom(Request $rq){
        $cat_id = SongCategory::where("name", $rq->formula)->first()?->id;
        $formula = $cat_id ? [$cat_id] : [1, 2, 3, 4];
        $part_match = [
            "1/_/_/_/_%",
            "_/1/_/_/_%",
            "_/_/1/_/_%",
            "_/_/_/1/_%",
            "_/_/_/_/1%",
        ];

        $data = Song::whereIn("song_category_id", $formula)
            ->where("preferences", "like", $part_match[$rq->part])
            ->get()
            ->random();
        return response()->json($data);
    }
    public function songSuggList(Request $rq){
        $categories = SongCategory::where("name", $rq->formula)
            ->orWhereIn("name", ["standard", "niestandard", "maryjne", "Serce"])
            ->pluck("id");

        $songs = Song::select(["title", "song_category_id"])
            ->whereIn("song_category_id", $categories)
            ->get()->toArray();

        return response()->json($songs);
    }

    public function songExportOpenSong(string $title_slug)
    {
        $song = Song::all()->filter(function($song) use ($title_slug){
            return Str::slug($song->title) === $title_slug;
        })->first();
        if(!$song) return abort(404);

        foreach (explode(Song::$VAR_SEP, $song->lyrics) as $orig_lyrics) {
            // transform lyrics
            $lyrics = Str::of($orig_lyrics)
                ->replaceMatches("/(\*\*|--)\s*/", "")
                ->replaceMatches("/\*(?=\s)/", "\r\n[C]")
                ->replaceMatches("/-\s+/", "||\r\n")
                ->replaceMatches("/(\d+)\.(?=\s)/", "\r\n[V$1]")
            ;

            $repeated_choruses = $lyrics->matchAll("/\[C\]\s*.*(\.\.\.|…)\s*/");
            if ($repeated_choruses->count()) {
                $presentation = $lyrics->matchAll("/\[(\w+)\]/")->join(" ");
                $lyrics = $lyrics->replaceMatches("/\[C\]\s*.*(\.\.\.|…)\s*/", ""); // remove extra choruses
            } else {
                foreach ($lyrics->matchAll("/\[C\]/") as $i => $chorus) {
                    $lyrics = $lyrics->replaceMatches("/\[C\]/", "[C" . $i+1 . "]", 1);
                }
                $presentation = $lyrics->matchAll("/\[(\w+)\]/")->join(" ");
            }

            $lyrics = $lyrics
                ->replaceMatches("/\n([^[\s])/", "\n $1") // add spacebar before text lines
                ->replaceMatches("/^\s*/", "") // remove line break at the start
                ->__toString()
            ;

            // create xml file
            $xml = new SimpleXMLElement('<song/>');
            $xml->addChild('title', $song->title);
            $xml->addChild('lyrics', $lyrics);
            $xml->addChild('author');
            $xml->addChild('copyright');
            $xml->addChild('hymn_number');
            $xml->addChild('presentation', $presentation);
            $xml->addChild('ccli');
            $capo = $xml->addChild('capo');
            $capo->addAttribute('print', "false");
            $capo->addAttribute('sharp', "true");
            $xml->addChild('key');
            $xml->addChild('aka');
            $xml->addChild('key_line');
            $xml->addChild('user1');
            $xml->addChild('user2');
            $xml->addChild('user3');
            $xml->addChild('theme');
            $xml->addChild('linked_songs');
            $xml->addChild('tempo');
            $xml->addChild('time_sig');
            $backgrounds = $xml->addChild('backgrounds');
            $backgrounds->addAttribute('resize', "screen");
            $backgrounds->addAttribute('keep_aspect', "false");
            $backgrounds->addAttribute('link', "false");
            $backgrounds->addAttribute('background_as_text', "false");

            $dom = dom_import_simplexml($xml)->ownerDocument;
            $dom->formatOutput = true;
            $xmlString = $dom->saveXML();
            $filename = $song->title;
            $headers = [
                'Content-Type' => "application/xml",
                'Content-Disposition' => "attachment; filename=$filename",
            ];
            return response($xmlString, 200, $headers);
        }
    }
}
