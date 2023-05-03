<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function sets(){
        $formulas = Formula::all();
        $sets = [];
        foreach($formulas as $formula){
            $sets[$formula->name] = $formula->user_sets;
        }

        return view("sets", array_merge(
            ["title" => "Dostępne zestawy"],
            compact("formulas", "sets")
        ));
    }

    public function setShow($set_id){
        $set = Set::findOrFail($set_id);

        return view("set", array_merge(
            ["title" => $set->name],
            compact("set")
        ));
    }
}
