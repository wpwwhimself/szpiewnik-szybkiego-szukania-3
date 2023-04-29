<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormulaController extends Controller
{
    public function formulas(){
        return view("ordinarium", ["title" => "Lista formuł"]);
    }
}
