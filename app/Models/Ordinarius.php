<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordinarius extends Model
{
    use HasFactory;

    protected $table = "ordinarium";

    protected $fillable = [
        "color_code", "part",
        "sheet_music",
    ];
    protected $appends = [
        "sheet_music_variants"
    ];

    public static $VAR_SEP = "\r\n%%%\r\n";

    public function getIsSpecialAttribute(){
        $colors = OrdinariusColor::all()->pluck("name")->toArray();
        return !in_array($this->color_code, array_merge($colors, ["*"]));
    }
    public function getSheetMusicVariantsAttribute(){
        return explode(self::$VAR_SEP, $this->sheet_music);
    }
}
