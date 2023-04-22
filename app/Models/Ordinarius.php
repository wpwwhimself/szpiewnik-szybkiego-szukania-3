<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordinarius extends Model
{
    use HasFactory;

    protected $table = "ordinarium";

    protected $fillable = [
        "color_code", "part",
        "sheet_music",
    ];
}
