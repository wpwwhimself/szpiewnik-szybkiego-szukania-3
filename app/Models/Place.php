<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = "string";
    protected $primaryKey = "name";

    protected $fillable = [
        "name",
        "notes",
    ];

    public function extras(){
        return $this->hasMany(PlaceExtra::class, "place", "name");
    }
    public function changes()
    {
        return $this->morphMany(Change::class, "changeable")->orderByDesc("date");
    }
}
