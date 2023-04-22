<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "formula", "color",
        "sIntro", "sOffer", "sCommunion", "sAdoration", "sDismissal",
        "pPsalm", "pAccl",
    ];

    public function formula(){
        return $this->hasOne(Formula::class);
    }
    public function color(){
        return $this->hasOne(OrdinariusColor::class);
    }
}
