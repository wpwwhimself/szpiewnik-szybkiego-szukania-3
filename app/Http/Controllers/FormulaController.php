<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\FormulaExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FormulaController extends Controller
{
    public function formulas(){
        $formulas = Formula::all();

        return view("formulas.list", array_merge(
            ["title" => "Lista formuł"],
            compact("formulas")
        ));
    }

    public function formula($name_slug){
        $formula = Formula::all()->filter(function($formula) use ($name_slug){
            return Str::slug($formula->name) === $name_slug;
        })->first();
        if(!$formula) return abort(404);
        $mass_order = collect(json_decode((new DataModController)->massOrder()->content()))
            ->pluck("label", "value")
            ->toArray();

        return view("formulas.edit", array_merge(
            ["title" => $formula->name." | Edycja formuły"],
            compact("formula", "mass_order")
        ));
    }

    public function formulaEdit(Request $rq){
        if($rq->action === "update"){
            Formula::where("name", $rq->old_name)->update([
                "name" => $rq->name,
            ]);
            for($i = 1; $i < count($rq->extraId); $i++){
                if($rq->song[$i]){
                    FormulaExtra::updateOrCreate(["id" => $rq->extraId[$i]], [
                        "name" => $rq->song[$i],
                        "label" => $rq->label[$i],
                        "before" => $rq->before[$i],
                        "replace" => $rq->replace[$i],
                        "formula" => $rq->name,
                    ]);
                }elseif($rq->extraId[$i]){
                    FormulaExtra::findOrFail($rq->extraId[$i])->delete();
                }
            }
            $response = "Formuła poprawiona";
        }else{
            FormulaExtra::where("formula", $rq->old_name)->delete();
            Formula::where("name", $rq->old_name)->delete();
            $response = "Formuła usunięta";
        }

        return redirect()->route("formulas")->with("toast", ["success", $response]);
    }

    public function formulaAdd(){
        if(!Auth::user()?->hasRole("formula-manager")) return back()->with("toast", ["error", "Nie masz uprawnień do utworzenia formuł"]);

        $new_formula_name = "--Nowa formuła--";
        if(!Formula::where("name", $new_formula_name)->count()) Formula::insert([
            "name" => $new_formula_name,
        ]);

        return redirect()->route("formula", ["name_slug" => Str::slug($new_formula_name)])->with("toast", ["success", "Szablon utworzony, dodaj formułę"]);
    }
}
