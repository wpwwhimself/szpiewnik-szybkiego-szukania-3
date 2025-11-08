<?php

namespace App\Models;

use App\Models\Shipyard\User as ShipyardUser;

class User extends ShipyardUser
{
    public const FROM_SHIPYARD = true;

    protected $fillable = [
        "name",
        "email",
        "default_place",
    ];
}
