<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\PlaceExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlaceController extends Controller
{
    public function places(){
        $places = Place::all();

        return view("places.list", array_merge(
            ["title" => "Dostępne miejsca"],
            compact("places")
        ));
    }

    public function place($name_slug){
        $place = Place::all()->filter(function($place) use ($name_slug){
            return Str::slug($place->name) === $name_slug;
        })->first();
        if(!$place) return abort(404);

        return view("places.edit", array_merge(
            ["title" => $place->name." | Edycja miejsca"],
            compact("place")
        ));
    }

    public function placeEdit(Request $rq){
        if($rq->action === "update"){
            $place = Place::find($rq->old_name);
            ChangeController::updateWithChange($place, "zmieniono", function () use ($rq, $place) {
               $place->update([
                   "name" => $rq->name,
                   "notes" => $rq->notes,
               ]);

               for($i = 1; $i < count($rq->extraId); $i++){
                   if($rq->song[$i]){
                       PlaceExtra::updateOrCreate(["id" => $rq->extraId[$i]], [
                           "name" => $rq->song[$i],
                           "label" => $rq->label[$i],
                           "before" => $rq->before[$i + 1],
                           "replace" => $rq->replace[$i],
                           "place" => $rq->name,
                       ]);
                   }elseif($rq->extraId[$i]){
                       PlaceExtra::findOrFail($rq->extraId[$i])->delete();
                   }
               }
            });
            $response = "Miejsce poprawione";
        }else{
            PlaceExtra::where("place", $rq->old_name)->delete();
            Place::where("name", $rq->old_name)->delete();
            $response = "Miejsce usunięte";
        }

        return redirect()->route("places")->with("toast", ["success", $response]);
    }

    public function placeAdd(){
        if(!Auth::user()?->hasRole("place-manager")) return back()->with("toast", ["error", "Nie masz uprawnień do utworzenia miejsca"]);

        $new_place_name = "--Nowe miejsce--";
        if(!Place::where("name", $new_place_name)->count()) Place::insert([
            "name" => $new_place_name,
        ]);

        ChangeController::add(Place::where("name", $new_place_name)->first(), "utworzono");

        return redirect()->route("place", ["name_slug" => Str::slug($new_place_name)])->with("toast", ["success", "Szablon utworzony, dodaj miejsce"]);
    }
}
