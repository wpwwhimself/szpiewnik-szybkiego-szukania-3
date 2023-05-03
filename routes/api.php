<?php

use App\Http\Controllers\DataModController;
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
});

Route::controller(SongController::class)->group(function(){
    Route::post("/song-suggestions", "songSuggestions")->name("get-song-suggestions");
});
