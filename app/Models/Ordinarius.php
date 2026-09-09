<?php

namespace App\Models;

use Wpwwhimself\Shipyard\Traits\HasStandardAttributes;
use Wpwwhimself\Shipyard\Traits\HasStandardFields;
use Wpwwhimself\Shipyard\Traits\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\Support\Str;

class Ordinarius extends Model
{
    use HasFactory;

    public const META = [
        "label" => "Części stałe",
        "icon" => "anchor",
        "description" => "",
        "role" => "ordinarius-manager",
        "ordering" => 11,
    ];

    protected $table = "ordinarium";

    protected $fillable = [
        "color_code", "part",
        "sheet_music",
    ];

    #region presentation
    public function __toString(): string
    {
        return implode(", ", [
            $this->color?->display_name ?? $this->color_code,
            $this->part,
        ]);
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->__toString(),
        );
    }

    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? self::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this->__toString(),
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.model.badges", [
                "badges" => $this->badges,
            ])->render(),
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.ordinarium.melody-preview", [
                "model" => $this,
            ])->render(),
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        // "<column_name>" => [
        //     "type" => "<input_type>",
        //     "columnTypes" => [ // for JSON
        //         "<label>" => "<input_type>",
        //     ],
        //     "selectData" => [ // for select
        //         "options" => ["label" => "", "value" => ""],
        //         "emptyOption" => "",
        //     ],
        //     "label" => "",
        //     "hint" => "",
        //     "icon" => "",
        //     // "required" => true,
        //     // "autofillFrom" => ["<route>", "<model_name>"],
        //     // "characterLimit" => 999, // for text fields
        //     // "hideForEntmgr" => true,
        //     // "role" => "",
        // ],
    ];

    public const CONNECTIONS = [
        // "<name>" => [
        //     "model" => ,
        //     "mode" => "<one|many>",
        //     // "field_name" => "",
        //     // "field_label" => "",
        // ],
    ];

    public const ACTIONS = [
    ];

    public const EXTRA_SECTIONS = [
        "contour" => [
            "title" => "Kontur",
            "icon" => "chart-line-variant",
            "component" => "ordinarium.contour-preview",
            "show-on" => "edit",
            "role" => "technical",
        ],
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
    #endregion

    #region attributes
    protected function casts(): array
    {
        return [
            //
        ];
    }

    protected $appends = [
        "sheet_music_variants",
        "contour",
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

    public function getIsSpecialAttribute(){
        $colors = OrdinariusColor::all()->pluck("name")->toArray();
        return !in_array($this->color_code, array_merge($colors, ["*"]));
    }

    public function getSheetMusicVariantsAttribute(){
        return explode(self::$VAR_SEP, $this->sheet_music);
    }

    public function contour(): Attribute
    {
        $music_strings = collect($this->sheet_music_variants)->map(fn ($sm) => Str::of(Str::of($sm)
                ->matchAll("/^(?![A-Z]:).*$/m") // skip technical lines
                ->join("")
            )
            ->replaceMatches("/!(fine|\w\.\w\.|fermata)!/", "")
            ->replaceMatches('/"[A-H][\w#\/]{0,4}"/', "")
        );
        $contours = [];
        foreach ($music_strings as $i => $ms) {
            preg_match_all("/[A-Za-z][,']*/", $ms, $matches);
            $contour = $matches[0];
            // translate pitches to indices
            $contour = array_map(
                function ($note) {
                    // just compare ord of note, lowercase are bigger anyway
                    $ord = ord($note);
                    $ord -= Str::substrCount($note, ",") * 32; // lower octaves
                    $ord += Str::substrCount($note, "'") * 32; // upper octaves
                    if (Str::match("/[ab]/i", $note)) $ord += 7; // put A and B in the right order
                    return $ord;
                },
                $contour
            );
            // compare indices, write contour
            $contour = collect($contour)->sliding(2)->map(fn ($comp) => [-1 => "+", 0 => "0", 1 => "-"][$comp->first() <=> $comp->last()])->join("");
            $contours[$i] = $contour;
        }

        return Attribute::make(
            get: fn () => $contours,
        );
    }
    #endregion

    #region relations
    public function color()
    {
        return $this->belongsTo(OrdinariusColor::class, "color_code", "name");
    }
    #endregion

    #region helpers
    public static $VAR_SEP = "\r\n%%%\r\n";
    #endregion
}
