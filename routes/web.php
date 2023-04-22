<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(HomeController::class)->group(function(){
    Route::get("/", "sets")->name("sets");
    Route::get("/set/{name}", "setShow")->name("set-show");

    Route::get("/songs", "songs")->name("songs");
    Route::get("/songs/{title}", "song")->name("song");

    Route::get("/ordinarium", "ordinarium")->name("ordinarium");
    Route::get("/formulas", "formulas")->name("formulas");
    Route::get("/places", "places")->name("places");
});

// Route::view("/auth/register", "auth.register");

// Route::view('/{path?}/{pathh?}/{pathhh?}', 'layout')->name("react");
