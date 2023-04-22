<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr;

class Formula extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        "name",
        "gloria_present",
    ];

    public function sets(){
        return $this->hasMany(Set::class, "formula", "name");
    }
    public function extras(){
        return $this->hasMany(Extra::class);
    }
}
