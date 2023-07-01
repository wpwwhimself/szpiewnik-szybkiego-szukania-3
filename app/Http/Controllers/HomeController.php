<?php

namespace App\Http\Controllers;

use App\Models\Clearance;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(){
        $places = Place::all();
        $maxClearance = Clearance::max("id");

        return view("home", array_merge(
            ["title" => null],
            compact("places", "maxClearance")
        ));
    }
}
