<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(){
        return view("home", array_merge(
            ["title" => null]
        ));
    }

    public function userUpdate(Request $rq){
        $updates = [
            "name" => $rq->name,
            "email" => $rq->email,
        ];
        if($rq->password){
            if(strlen($rq->password) < 8) return back()->with("error", "Hasło musi mieć minimum 8 znaków.");
            $updates["password"] = Hash::make($rq->password);
        };

        User::find(Auth::id())->update($updates);

        return back()->with("success", "Dane użytkownika zmienione");
    }
}
