<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongCategory extends Model
{
    use HasFactory;

    protected $table = "song_categories";

    protected $fillable = [
        "name"
    ];

    public function songs(){
        return $this->hasMany(Song::class);
    }
}
