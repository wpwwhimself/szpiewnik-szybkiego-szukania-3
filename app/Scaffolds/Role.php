<?php

namespace App\Scaffolds;

use App\Scaffolds\Shipyard\Role as ShipyardRole;

class Role extends ShipyardRole
{
    protected static function items(): array
    {
        return [
            [
                "name" => "song-manager",
                "icon" => "file-music",
                "description" => "Ma dostęp do edycji pieśni",
            ],
            [
                "name" => "set-manager",
                "icon" => "tray-full",
                "description" => "Ma dostęp do edycji zestawów",
            ],
            [
                "name" => "ordinarius-manager",
                "icon" => "anchor",
                "description" => "Ma dostęp do edycji części stałych",
            ],
            [
                "name" => "place-manager",
                "icon" => "map-marker",
                "description" => "Ma dostęp do edycji miejsc",
            ],
            [
                "name" => "formula-manager",
                "icon" => "book",
                "description" => "Ma dostęp do edycji formuł",
            ],
        ];
    }
}
