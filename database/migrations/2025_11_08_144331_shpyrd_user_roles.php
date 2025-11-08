<?php

use App\Models\Shipyard\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ([
            [
                "name" => "set-manager",
                "icon" => model_icon("sets"),
                "description" => "Ma dostęp do edycji zestawów.",
            ],
            [
                "name" => "song-manager",
                "icon" => model_icon("songs"),
                "description" => "Ma dostęp do edycji pieśni.",
            ],
            [
                "name" => "ordinarius-manager",
                "icon" => model_icon("ordinariuses"),
                "description" => "Ma dostęp do edycji części stałych.",
            ],
            [
                "name" => "place-manager",
                "icon" => model_icon("places"),
                "description" => "Ma dostęp do edycji miejsc.",
            ],
            [
                "name" => "formula-manager",
                "icon" => model_icon("formulas"),
                "description" => "Ma dostęp do edycji formuł.",
            ],
        ] as $role) {
            Role::create($role);
        }

        $users = User::all();
        foreach ($users as $user) {
            $user->roles()->attach(["set-manager", "place-manager"]);
            if ($user->clearance_id >= 2) {
                $user->roles()->attach(["song-manager", "ordinarius-manager"]);
            }
            if ($user->clearance_id >= 3) {
                $user->roles()->attach(["formula-manager"]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
