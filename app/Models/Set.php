<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Set extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "formula", "color",
        "user_id", "public",
        "sIntro", "sOffer", "sCommunion", "sAdoration", "sDismissal",
        "pPsalm", "pAccl",
    ];

    public function getAllSongsAttribute(){
        $songs = [
            $this->sIntro ? preg_split("/\r?\n/", $this->sIntro) : null,
            $this->sOffer ? preg_split("/\r?\n/", $this->sOffer) : null,
            $this->sCommunion ? preg_split("/\r?\n/", $this->sCommunion) : null,
            $this->sAdoration ? preg_split("/\r?\n/", $this->sAdoration) : null,
            $this->sDismissal ? preg_split("/\r?\n/", $this->sDismissal) : null,
        ];
        return $songs;
    }

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
    public function changes()
    {
        return $this->morphMany(Change::class, "changeable")->orderByDesc("date");
    }
}
