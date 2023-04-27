<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Auth\Middleware\Authenticate;
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

Route::controller(AuthController::class)->group(function(){
    Route::get('/auth', "input")->name("login");
    Route::post('/auth-back', "authenticate")->name("authenticate");
    Route::post('/auth/register-back', "register")->name("register");
    Route::get('/auth/logout', "logout")->name("logout");
});

  Route::controller(HomeController::class)->group(function(){
    Route::get("/", "sets")->name("sets");
    Route::get("/set/{song_id}", "setShow")->name("set-show");

    Route::get("/songs", "songs")->name("songs");
    Route::get("/songs/{title_slug}", "song")->name("song");

    Route::get("/ordinarium", "ordinarium")->name("ordinarium");
    Route::get("/ordinarium/{color}_{part}", "ordinarius")->name("ordinarius");

    Route::get("/formulas", "formulas")->name("formulas");
    Route::get("/places", "places")->name("places");

    Route::middleware(Authenticate::class)->group(function(){
      Route::post("/songs/edit", "songEdit")->name("song-edit");
    });
});
