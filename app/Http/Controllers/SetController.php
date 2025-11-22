<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\OrdinariusColor;
use App\Models\Set;
use App\Models\SetExtra;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetController extends Controller
{
    public function sets(){
        $formulas = Formula::all();
        $sets = [];
        foreach($formulas as $formula){
            $sets[$formula->name] = $formula->user_sets;
        }

        return view("sets.list", array_merge(
            ["title" => "Dostępne zestawy"],
            compact("formulas", "sets")
        ));
    }

    public function setPresent($set_id){
        $set = Set::findOrFail($set_id);

        return view("sets.present", array_merge(
            ["title" => $set->name],
            compact("set")
        ));
    }

    public function set($set_id){
        $set = Set::findOrFail($set_id);

        if ($set->user_id !== Auth::id()) {
            return abort(403, "Nie możesz edytować tego zestawu.");
        }

        $formulas = Formula::all()->map(fn ($f) => ["label" => $f->name, "value" => $f->name]);
        $colors = OrdinariusColor::all()->pluck("display_name", "name");
        $songs = Song::all()->pluck("title", "title");
        $song_preferences = Song::all()->pluck("preferences", "title");
        $last_set = Set::orderByDesc("updated_at")
            ->where("id", "<>", $set_id)
            ->where("user_id", Auth::id())
            ->where("formula", $set->formula)
            ->first();

        return view("sets.edit", array_merge(
            ["title" => $set->name . " | Edycja mszy"],
            compact("set", "formulas", "colors", "songs", "song_preferences", 'last_set')
        ));
    }

    public function setEdit(Request $rq){
        $set = Set::findOrFail($rq->id);

        if($rq->action === "update"){
            ChangeController::updateWithChange($set, "zmieniono", function () use ($rq, $set) {
                $set->update([
                    "name" => $rq->name,
                    "formula" => $rq->formula,
                    "color" => $rq->color,
                    "public" => $rq->has("public"),
                    "sIntro" => $rq->sIntro,
                    "sOffer" => $rq->sOffer,
                    "sCommunion" => $rq->sCommunion,
                    "sAdoration" => $rq->sAdoration,
                    "sDismissal" => $rq->sDismissal,
                    "pPsalm" => $rq->pPsalm,
                    "pAccl" => $rq->pAccl,
                ]);
                for($i = 1; $i < count($rq->extraId); $i++){
                    if($rq->song[$i]){
                        SetExtra::updateOrCreate(["id" => $rq->extraId[$i]], [
                            "name" => $rq->song[$i],
                            "label" => $rq->label[$i],
                            "before" => $rq->before[$i],
                            "replace" => $rq->replace[$i],
                            "set_id" => $rq->id,
                        ]);
                    }elseif($rq->extraId[$i]){
                        SetExtra::findOrFail($rq->extraId[$i])->delete();
                    }
                }
            }, ["extras"]);
            $response = "Msza poprawiona";

            // filling preferences
            foreach($set->all_songs as $pref_id => $songs){
                if($songs === null) continue;
                foreach($songs as $title){
                    $song = Song::where("title", $title)->first();
                    if (!$song) continue;
                    $preferences = explode("/", $song->preferences);
                    if($preferences[$pref_id] == 0){
                        $preferences[$pref_id] = 1;
                        Song::where("title", $title)->where("preferences", $song->preferences)
                            ->update([
                            "preferences" => implode("/", $preferences),
                        ]);
                    }
                }
            }
        }elseif($rq->action === "delete"){
            SetExtra::where("set_id", $rq->id)->delete();
            $set->delete();
            $response = "Msza usunięta";
        }

        return redirect()->route("sets")->with("toast", ["success", $response]);
    }

    public function setAdd(){
        $new_set = Set::create([
            "user_id" => Auth::id(),
            "public" => false,
            "name" => "--Nowa msza--",
            "formula" => "zwykła",
            "color" => "green",
        ]);

        ChangeController::add($new_set, "utworzono");

        return redirect()->route("set", ["set_id" => $new_set])->with("toast", ["success", "Szablon utworzony, dodaj mszę"]);
    }

    public function setCopyForUser(Set $set)
    {
        $new_set = Set::create([
            "user_id" => Auth::id(),
            "public" => false,
            "name" => $set->name,
            "formula" => $set->formula,
            "color" => $set->color,
            "sIntro" => $set->sIntro,
            "sOffer" => $set->sOffer,
            "sCommunion" => $set->sCommunion,
            "sAdoration" => $set->sAdoration,
            "sDismissal" => $set->sDismissal,
            "pPsalm" => $set->pPsalm,
            "pAccl" => $set->pAccl,
        ]);
        SetExtra::insert($set->extras->map(fn ($ex) => [
            "set_id" => $new_set->id,
            "name" => $ex->name,
            "label" => $ex->label,
            "before" => $ex->before,
            "replace" => $ex->replace,
        ])->toArray());

        ChangeController::add($new_set, "skopiowano z istniejącego");

        return redirect()->route("set", ["set_id" => $new_set])->with("toast", ["success", "Msza skopiowana, możesz ją teraz dopasować pod siebie"]);
    }
}
