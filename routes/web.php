<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdinariusController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\SetController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SpellbookController;
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
    Route::prefix("auth")->group(function(){
        Route::get('/login', "input")->name("login");
        Route::post('/login/process', "authenticate")->name("authenticate");
        Route::get("/register", "register")->name("register");
        Route::post('/register/process', "registerProcess")->name("register-process");

        Route::middleware(Authenticate::class)->group(function(){
            Route::get('/logout', "logout")->name("logout");
            Route::post("/user-update", "userUpdate")->name("user-update");
        });
    });
});

Route::controller(SpellbookController::class)->group(function(){
    foreach (SpellbookController::SPELLS as $name => $route) {
        Route::get($route, $name);
    }
});

Route::controller(HomeController::class)->group(function(){
    Route::get("/", "index")->name("home");
});

Route::controller(SetController::class)->prefix("sets")->group(function(){
    Route::get("/", "sets")->name("sets");
    Route::get("/present/{set_id}", "setPresent")->name("set-present");
});

Route::controller(SongController::class)->prefix("songs")->group(function(){
    Route::get("/", "songs")->name("songs");
    Route::get("/present/{title_slug}", "songPresent")->name("song-present");
});

Route::controller(OrdinariusController::class)->prefix("ordinarium")->group(function(){
    Route::get("/", "ordinarium")->name("ordinarium");
    Route::get("/present/{color}", "ordinariusPresent")->name("ordinarius-present");
});

Route::middleware(Authenticate::class)->group(function(){
    Route::controller(SetController::class)->prefix("sets")->group(function(){
        Route::get("/show/{set_id}", "set")->name("set");
        Route::post("/edit", "setEdit")->name("set-edit");
        Route::get("/add", "setAdd")->name("set-add");
        Route::get("/copy-for-user/{set}", "setCopyForUser")->name("set-copy-for-user");
    });

    Route::controller(SongController::class)->prefix("songs")->group(function(){
        Route::get("/show/{title_slug}", "song")->name("song");
        Route::post("/edit", "songEdit")->name("song-edit");
        Route::get("/add", "songAdd")->name("song-add");
        Route::get("/export/opensong/{title_slug}", "songExportOpenSong")->name("song-export-opensong");
    });

    Route::controller(OrdinariusController::class)->prefix("ordinarium")->group(function(){
        Route::get("/show/{color_code}_{part}", "ordinarius")->name("ordinarius");
        Route::post("/edit", "ordinariusEdit")->name("ordinarius-edit");
    });

    Route::controller(FormulaController::class)->prefix("formulas")->group(function(){
        Route::get("/", "formulas")->name("formulas");
        Route::get("/show/{name_slug}", "formula")->name("formula");
        Route::post("/edit", "formulaEdit")->name("formula-edit");
        Route::get("/add", "formulaAdd")->name("formula-add");
    });

    Route::controller(PlaceController::class)->prefix("places")->group(function(){
        Route::get("/", "places")->name("places");
        Route::get("/show/{name_slug}", "place")->name("place");
        Route::post("/edit", "placeEdit")->name("place-edit");
        Route::get("/add", "placeAdd")->name("place-add");
    });
});
