<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function input(){
        return view("auth.login", [
            "title" => "Logowanie",
        ]);
    }

    public function authenticate(Request $request){
        $credentials = $request->only(["name", "password"]);

        if (Auth::attempt($credentials, $request->has("remember"))) {
            $request->session()->regenerate();

            return redirect()->intended("/")->with("success", "Zalogowano");
        }

        return back()->with("error", "Nieprawidłowe dane logowania");
    }

    public function register(){
        return view("auth.register", [
            "title" => "Rejestracja",
        ]);
    }

    public function registerProcess(Request $rq){
        if(User::where("email", $rq->email)->first()) return back()->with("error", "Taki adres mailowy już jest zapisany.");

        if($rq->spamtest != 4*6) return back()->with("error", "Cztery razy sześć nie równa się $rq->spamtest!");

        if(strlen($rq->password) < 8) return back()->with("error", "Hasło musi mieć minimum 8 znaków.");
        if($rq->password_confirm != $rq->password) return back()->with("error", "Hasła nie są sobie równe!");

        $user = User::create([
            'name' => $rq->name,
            'email' => $rq->email,
            'password' => Hash::make($rq->password),
        ]);

        Auth::login($user);

        return redirect()->route("home")->with("success", "Konto gotowe, witaj na pokładzie!");
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/")->with("success", "Wylogowano");
    }

    public function userUpdate(Request $rq){
        $updates = [
            "name" => $rq->name,
            "email" => $rq->email,
            "default_place" => $rq->default_place,
        ];
        if($rq->password){
            if(strlen($rq->password) < 8) return back()->with("error", "Hasło musi mieć minimum 8 znaków.");
            $updates["password"] = Hash::make($rq->password);
        };

        User::find(Auth::id())->update($updates);

        return back()->with("success", "Dane użytkownika zmienione");
    }
}
