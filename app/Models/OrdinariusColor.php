<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinariusColor extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        "name", "display_name",
        "display_color",
        "desc",
    ];

    public function ordinarium(){
        return $this->hasMany(Ordinarius::class, "color_code", "name");
    }
}
