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
    protected $appends = [
        "sheet_music_variants", "lyrics_variants",
    ];

    public static $VAR_SEP = "\r\n%%%\r\n";

    public function getSheetMusicVariantsAttribute(){
        return explode(self::$VAR_SEP, $this->sheet_music);
    }
    public function getLyricsVariantsAttribute(){
        return explode(self::$VAR_SEP, $this->lyrics);
    }

    public function category(){
        return $this->belongsTo(SongCategory::class);
    }
}
