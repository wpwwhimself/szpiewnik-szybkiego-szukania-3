<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Formula extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        "name",
    ];

    public function sets(){
        return $this->hasMany(Set::class, "formula", "name");
    }
    public function user_sets(){
        return $this->hasMany(Set::class, "formula", "name")
            ->where("user_id", Auth::id())
            ->orWhere("public", true)
            ->where("formula", $this->name);
    }
    public function extras(){
        return $this->hasMany(FormulaExtra::class, "formula", "name");
    }
}
