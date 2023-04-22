<?php

namespace App\Http\Controllers;

use App\Models\Formula;
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
        return view("ordinarium", ["title" => "Lista części stałych"]);
    }

    public function formulas(){
        return view("ordinarium", ["title" => "Lista formuł"]);
    }

    public function places(){
        return view("places", ["title" => "Lista miejsc"]);
    }
}
