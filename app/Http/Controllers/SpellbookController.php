<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpellbookController extends Controller
{
    public const SPELLS = [
        "become" => "become/{user_id}",
    ];

    private function checkIfUserCanCastSpells() {
        $can = Auth::user()->clearance->id >= 3;
        if (!$can) abort(403, "Nie masz uprawnień do rzucania zaklęć");
    }

    public function become($user_id)
    {
        $this->checkIfUserCanCastSpells();

        $user = User::find($user_id);
        Auth::login($user);
        return redirect()->route("home")->with("success", "Zalogowano jako " . $user->name);
    }
}
