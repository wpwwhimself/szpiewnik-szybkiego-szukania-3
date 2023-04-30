<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Ordinarius;
use App\Models\OrdinariusColor;
use App\Models\Set;
use App\Models\Song;
use App\Models\SongCategory;
use Illuminate\Http\Request;

class DataModController extends Controller
{
    public function setData(Request $rq){
        $set = Set::find($rq->set_id);

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
        ]);
    }

    public function ordinarium(){
        return response()->json(Ordinarius::all());
    }
}
