<?php

use App\Http\Controllers\DataModController;
use App\Http\Controllers\SetController;
use App\Http\Controllers\SongController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(DataModController::class)->group(function(){
    Route::get("/set-data", "setData")->name("get-set-data");
    Route::get("/ordinarium", "ordinarium")->name("get-ordinarium");
    Route::get("/song-data", "songData")->name("get-song-data");
    Route::get("/mass-order", "massOrder")->name("get-mass-order");
    Route::get("/ordinarius-data", "ordinariusData")->name("get-ordinarius-data");

    Route::post("/set-notes", "processSetNote");
    Route::post("/song-notes", "processSongNote");
});

Route::controller(SongController::class)->group(function(){
    Route::post("/song-autocomplete", "songAutocomplete")->name("get-song-autocomplete");
    Route::post("/song-random", "songRandom")->name("get-song-random");
});
