<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "can"
    ];

    public function users(){
        return $this->hasMany(User::class);
    }
    public function getAllCanAttribute(){
        return Clearance::where("id", "<=", $this->id)->pluck("can")->toArray();
    }
}
