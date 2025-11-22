<?php

namespace App\Models;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\ComponentAttributeBag;

class Song extends Model
{
    use HasFactory;

    public const META = [
        "label" => "Pieśni i utwory",
        "icon" => "file-music",
        "description" => "",
        "role" => "song-manager",
        "ordering" => 1,
    ];

    public $incrementing = false;
    protected $keyType = "string";
    protected $primaryKey = "title";

    protected $fillable = [
        "title",
        "song_category_id", "category_desc",
        "number_preis",
        "key", "preferences",
        "lyrics", "sheet_music",
    ];

    #region presentation
    public function __toString(): string
    {
        return $this->title;
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->title,
        );
    }

    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? self::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this->title,
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.model.badges", [
                "badges" => $this->badges,
            ])->render(),
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.model.connections-preview", [
                "connections" => self::getConnections(),
                "model" => $this,
            ])->render(),
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "title" => [
            "type" => "text",
            "label" => "Tytuł",
            "icon" => "badge-account",
        ],
        "category_desc" => [
            "type" => "text",
            "label" => "Opis kategorii",
            "icon" => "note",
            "hint" => "Kategoria ze śpiewnika albo dodatkowy opis.",
        ],
        "number_preis" => [
            "type" => "text",
            "label" => "Numer w projektorze Preis",
            "icon" => "projector",
        ],
        "key" => [
            "type" => "text",
            "label" => "Tonacja",
            "icon" => "music-clef-treble",
        ],
    ];

    public const CONNECTIONS = [
        "category" => [
            "model" => SongCategory::class,
            "mode" => "one",
            "field_name" => "song_category_id",
            "field_label" => "Kategoria",
        ],
    ];

    public const ACTIONS = [
        // [
        //     "icon" => "",
        //     "label" => "",
        //     "show-on" => "<list|edit>",
        //     "route" => "",
        //     "role" => "",
        //     "dangerous" => true,
        // ],
    ];
    #endregion

    // use CanBeSorted;
    public const SORTS = [
        // "<name>" => [
        //     "label" => "",
        //     "compare-using" => "function|field",
        //     "discr" => "<function_name|field_name>",
        // ],
    ];

    public const FILTERS = [
        // "<name>" => [
        //     "label" => "",
        //     "icon" => "",
        //     "compare-using" => "function|field",
        //     "discr" => "<function_name|field_name>",
        //     "mode" => "<one|many>",
        //     "operator" => "",
        //     "options" => [
        //         "<label>" => <value>,
        //     ],
        // ],
    ];

    #region scopes
    use HasStandardScopes;

    public function scopeForSetPicker($query, ?string $pref = null)
    {
        if (!$pref) {
            return $query;
        }

        $preference_pattern = [
            "intro" => "1/_/_/_/_%",
            "offer" => "_/1/_/_/_%",
            "communion" => "_/_/1/_/_%",
            "adoration" => "_/_/_/1/_%",
            "dismissal" => "_/_/_/_/1%",
        ][$pref];

        return $query->where("preferences", "like", $preference_pattern);
    }
    #endregion

    #region attributes
    protected function casts(): array
    {
        return [
            //
        ];
    }

    protected $appends = [
        "sheet_music_variants", "lyrics_variants",
    ];

    use HasStandardAttributes;

    // public function badges(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => [
    //             [
    //                 "label" => "",
    //                 "icon" => "",
    //                 "class" => "",
    //                 "style" => "",
    //                 "condition" => "",
    //             ],
    //             [
    //                 "html" => "",
    //             ],
    //         ],
    //     );
    // }

    public function getSheetMusicVariantsAttribute(){
        return explode(self::$VAR_SEP, $this->sheet_music);
    }

    public function getLyricsVariantsAttribute(){
        return explode(self::$VAR_SEP, $this->lyrics);
    }
    #endregion

    #region relations
    public function category(){
        return $this->belongsTo(SongCategory::class);
    }
    public function changes()
    {
        return $this->morphMany(Change::class, "changeable")->orderByDesc("date");
    }
    public function notes()
    {
        return $this->hasMany(SongNote::class, "title");
    }
    public function notesForCurrentUser()
    {
        return $this->hasMany(SongNote::class)->where("user_id", Auth::id());
    }
    #endregion

    #region helpers
    public static $VAR_SEP = "\r\n%%%\r\n";
    #endregion
}
