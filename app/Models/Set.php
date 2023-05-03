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

    public function formulaData(){
        return $this->belongsTo(Formula::class, "formula", "name");
    }
    public function color(){
        return $this->hasOne(OrdinariusColor::class);
    }
    public function extras(){
        return $this->hasMany(SetExtra::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
