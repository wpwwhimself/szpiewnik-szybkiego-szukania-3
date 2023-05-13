<?php

namespace App\Http\Controllers;

use App\Models\Ordinarius;
use App\Models\OrdinariusColor;
use App\Models\Place;
use App\Models\Set;
use App\Models\Song;
use App\Models\SongCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataModController extends Controller
{
    public function setData(Request $rq){
        $set = Set::find($rq->set_id);
        $place = Place::all()->filter(fn($el) => Str::slug($el->name) === $rq->place_slug)->first();

        return response()->json([
            "set" => collect(
                $set,
                ["extras" => $set->extras]
            ),
            "ordinarius_colors" => OrdinariusColor::all(),
            "ordinarium" => Ordinarius::all(),
            "formula" => collect(
                $set->formulaData,
                ["extras" => $set->formulaData->extras]
            ),
            "songs" => Song::all(),
            "categories" => SongCategory::all(),
            "place_extras" => $place?->extras,
            "places" => Place::all(),
        ]);
    }

    public function ordinarium(){
        return response()->json(Ordinarius::all());
    }

    public function massOrder(){
        return response()->json([
            [ "value" => "sIntro", "label" => "Wejście"],
            [ "value" => "oKyrie", "label" => "Kyrie"],
            [ "value" => "oGloria", "label" => "Gloria"],
            [ "value" => "xLUP1", "label" => "Módlmy się"],
            [ "value" => "xReading1", "label" => "1. czytanie"],
            [ "value" => "pPsalm", "label" => "Psalm"],
            [ "value" => "xReading2", "label" => "2. czytanie"],
            [ "value" => "pAccl", "label" => "Aklamacja"],
            [ "value" => "xEvang", "label" => "Ewangelia"],
            [ "value" => "xHomily", "label" => "Kazanie"],
            [ "value" => "oCredo", "label" => "Credo"],
            [ "value" => "xGI", "label" => "Modlitwa Powszechna"],
            [ "value" => "sOffer", "label" => "Przygotowanie Darów"],
            [ "value" => "oSanctus", "label" => "Sanctus"],
            [ "value" => "xTransf", "label" => "Przemienienie"],
            [ "value" => "oPaterNoster", "label" => "Ojcze nasz"],
            [ "value" => "oAgnusDei", "label" => "Agnus Dei"],
            [ "value" => "sCommunion", "label" => "Komunia"],
            [ "value" => "sAdoration", "label" => "Uwielbienie"],
            [ "value" => "xLUP2", "label" => "Módlmy się"],
            [ "value" => "xAnnounc", "label" => "Ogłoszenia"],
            [ "value" => "xBlessing", "label" => "Błogosławieństwo"],
            [ "value" => "sDismissal", "label" => "Zakończenie"],
        ]);
    }
}
