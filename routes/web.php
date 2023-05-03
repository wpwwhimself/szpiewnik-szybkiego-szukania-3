<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdinariusController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\SetController;
use App\Http\Controllers\SongController;
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
    Route::get('/auth/logout', "logout")->name("logout")->middleware(Authenticate::class);
});

Route::controller(HomeController::class)->group(function(){
    Route::get("/", "index")->name("home");
});

Route::controller(SetController::class)->prefix("sets")->group(function(){
    Route::get("/", "sets")->name("sets");
    Route::get("/present/{set_id}", "setPresent")->name("set-present");
});

Route::middleware(Authenticate::class)->group(function(){
    Route::controller(SetController::class)->prefix("sets")->group(function(){
        Route::get("/show/{set_id}", "set")->name("set");
        Route::post("/edit", "setEdit")->name("set-edit");
        Route::get("/add", "setAdd")->name("set-add");
    });

    Route::controller(SongController::class)->prefix("songs")->group(function(){
        Route::get("/", "songs")->name("songs");
        Route::get("/show/{title_slug}", "song")->name("song");
        Route::post("/edit", "songEdit")->name("song-edit");
        Route::get("/add", "songAdd")->name("song-add");
    });

    Route::controller(OrdinariusController::class)->prefix("ordinarium")->group(function(){
        Route::get("/", "ordinarium")->name("ordinarium");
        Route::get("/show/{color_code}_{part}", "ordinarius")->name("ordinarius");
        Route::post("/edit", "ordinariusEdit")->name("ordinarius-edit");
    });

    Route::controller(FormulaController::class)->prefix("formula")->group(function(){
        Route::get("/", "formulas")->name("formulas");

    });

    Route::controller(PlaceController::class)->prefix("places")->group(function(){
        Route::get("/", "places")->name("places");

    });
});
