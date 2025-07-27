<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinariusColor extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = "name";
    protected $keyType = "string";

    protected $fillable = [
        "name", "display_name",
        "display_color",
        "desc",
        "ordering",
    ];

    public function ordinarium(){
        return $this->hasMany(Ordinarius::class, "color_code", "name")
            ->orderByRaw(
                "case
                    when part regexp '^kyrie' then 1
                    when part regexp '^gloria' then 2
                    when part regexp '^psalm' then 3
                    when part regexp '^aklamacja' then 4
                    when part regexp '^sanctus' then 5
                    when part regexp '^agnus-dei' then 6
                else 99 end"
            );
    }

    public function getDisplayColorAttribute($val){
        return $val ?? $this->name;
    }
}
