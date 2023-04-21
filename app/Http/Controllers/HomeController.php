<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function sets(){
        $formulas = [];
        $sets = [];
        //todo ausfüllen

        return view("sets", array_merge(
            ["title" => "Dostępne zestawy"],
            compact("formulas", "sets")
        ));
    }

    public function songs(){
        return view("songs", ["title" => "Lista pieśni"]);
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
