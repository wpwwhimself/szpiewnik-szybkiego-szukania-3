<?php

namespace App\Models;

use Wpwwhimself\Shipyard\Models\User as ShipyardUser;

class User extends ShipyardUser
{
    public const FROM_SHIPYARD = true;

    public function __construct()
    {
        $this->mergeFillable([
            "default_place",
        ]);
    }
}
