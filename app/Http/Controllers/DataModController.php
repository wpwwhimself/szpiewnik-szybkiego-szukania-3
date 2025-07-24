<?php

namespace App\Http\Controllers;

use App\Models\Ordinarius;
use App\Models\OrdinariusColor;
use App\Models\Place;
use App\Models\Set;
use App\Models\SetNote;
use App\Models\Song;
use App\Models\SongCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataModController extends Controller
{
    public function setData(Request $rq){
        $set = Set::find($rq->set_id);
        $place = Place::with("extras")->get()->filter(fn($el) => Str::slug($el->name) === $rq->place_slug)->first();

        return response()->json([
            "set" => collect(
                $set,
                ["extras" => $set->extras]
            ),
            "ordinarius_colors" => OrdinariusColor::orderBy("display_name")->get(),
            "ordinarium" => Ordinarius::all(),
            "formula" => collect(
                $set->formulaData,
                ["extras" => $set->formulaData->extras]
            ),
            "songs" => Song::all(),
            "categories" => SongCategory::where("name", $set->formula)
                ->orWhereIn("name", ["standard", "niestandard", "maryjne", "Serce"])
                ->get(),
            "place" => $place,
            "places" => Place::all(),
        ]);
    }

    public function songData(Request $rq){
        $title_slug = $rq->title_slug;
        $song = Song::all()->filter(function($song) use ($title_slug){
            return Str::slug($song->title) === $title_slug;
        })->first();

        return response()->json([
            "song" => $song,
        ]);
    }

    public function ordinariusData(Request $rq){
        $part_order = [];
        $ordinarium = Ordinarius::where("color_code", $rq->color)
            ->orderByRaw("FIELD(part, 'kyrie', 'gloria', 'psalm', 'aklamacja', 'sanctus', 'agnus-dei')")
            ->get()
        ;

        return response()->json([
            "ordinarium" => $ordinarium,
        ]);
    }

    public function ordinarium(){
        return response()->json(Ordinarius::all());
    }

    public function massOrder(){
        return response()->json([
            [ "value" => "sIntro", "label" => "Wejście"],
            [ "value" => "xGreetings", "label" => "Powitanie"],
            [ "value" => "!Kyrie", "label" => "Kyrie"],
            [ "value" => "!Gloria", "label" => "Gloria"],
            [ "value" => "xLUP1", "label" => "Módlmy się"],
            [ "value" => "xReading1", "label" => "Czytanie (Stary Testament)"],
            [ "value" => "pPsalm", "label" => "Psalm"],
            [ "value" => "xReading2", "label" => "Czytanie (Nowy Testament)"],
            [ "value" => "pAccl", "label" => "Aklamacja"],
            [ "value" => "xEvang", "label" => "Ewangelia"],
            [ "value" => "xHomily", "label" => "Kazanie"],
            [ "value" => "!Credo", "label" => "Credo"],
            [ "value" => "xGI", "label" => "Modlitwa Powszechna"],
            [ "value" => "sOffer", "label" => "Przygotowanie Darów"],
            [ "value" => "!Sanctus", "label" => "Sanctus"],
            [ "value" => "xTransf", "label" => "Przemienienie"],
            [ "value" => "!PaterNoster", "label" => "Ojcze nasz"],
            [ "value" => "!AgnusDei", "label" => "Agnus Dei"],
            [ "value" => "sCommunion", "label" => "Komunia"],
            [ "value" => "sAdoration", "label" => "Uwielbienie"],
            [ "value" => "xLUP2", "label" => "Módlmy się"],
            [ "value" => "xAnnounc", "label" => "Ogłoszenia"],
            [ "value" => "xBlessing", "label" => "Błogosławieństwo"],
            [ "value" => "sDismissal", "label" => "Zakończenie"],
        ]);
    }

    public function processSetNote(Request $rq){
        if (User::find($rq->input("user_id"))?->clearance_id < 1) return response()->json([
            "success" => false,
            "message" => "Nie masz uprawnień do edycji notatek",
        ], 403);

        if (empty($rq->input("content"))) {
            SetNote::where("set_id", $rq->input("set_id"))
                ->where("user_id", $rq->input("user_id"))
                ->where("element_code", $rq->input("element_code"))
                ->delete();
            return response()->json([
                "success" => true,
                "message" => "Notatka usunięta",
            ]);
        } else {
            $note = SetNote::updateOrCreate([
                "set_id" => $rq->input("set_id"),
                "user_id" => $rq->input("user_id"),
                "element_code" => $rq->input("element_code"),
            ], [
                "content" => $rq->input("content"),
            ]);
            return response()->json([
                "success" => true,
                "message" => "Notatka zapisana",
                "note" => $note,
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "Błąd w zapisie notatki",
        ], 500);
    }
}
