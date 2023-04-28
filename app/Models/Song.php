<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = "string";
    protected $primaryKey = null;

    protected $fillable = [
        "title",
        "song_category_id", "category_desc",
        "number_preis",
        "key", "preferences",
        "lyrics", "sheet_music",
    ];

    public function category(){
        return $this->belongsTo(SongCategory::class);
    }
}
