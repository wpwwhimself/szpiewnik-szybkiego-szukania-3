<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceExtra extends Model
{
    use HasFactory;

    protected $table = "place_extras";

    protected $fillables = [
        "place",
        "name", "before", "replace",
    ];


}
