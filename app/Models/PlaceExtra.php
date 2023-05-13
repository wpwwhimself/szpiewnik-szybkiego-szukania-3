<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        "place",
        "name", "before", "replace",
    ];
}
