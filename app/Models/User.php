<?php

namespace App\Models;

use Wpwwhimself\Shipyard\Models\User as ShipyardUser;

class User extends ShipyardUser
{
    public const FROM_SHIPYARD = true;

    protected $fillable = [
        "name",
        "email",
        "roles",
        "default_place",
    ];
}
