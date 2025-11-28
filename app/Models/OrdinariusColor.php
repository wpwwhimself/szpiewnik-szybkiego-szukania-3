<?php

namespace App\Models;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\View\ComponentAttributeBag;

class OrdinariusColor extends Model
{
    use HasFactory;

    public const META = [
        "label" => "Kolory części stałych",
        "icon" => "palette",
        "description" => "Msze lub zestawy części stałych.",
        "role" => "technical",
        "ordering" => 12,
    ];

    public $incrementing = false;
    protected $primaryKey = "name";
    protected $keyType = "string";

    protected $fillable = [
        "name", "display_name",
        "display_color",
        "desc",
        "ordering",
    ];

    #region presentation
    public function __toString(): string
    {
        return $this->display_name ?? $this->name;
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this ($this->name)",
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
                "slot" => $this->__toString(),
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
            get: fn () => view("components.shipyard.app.model.field-value", [
                "field" => "ordering",
                "model" => $this,
            ])->render(),
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "ordering" => [
            "type" => "number",
            "label" => "Kolejność",
            "hint" => "Dyktuje kolejność kolorów. Im dalej w kolejności, tym bardziej uroczyście.",
            "icon" => "sort",
        ],
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

    public function scopeOrdered($query)
    {
        return $query->orderBy("ordering");
    }

    public function scopeForConnection($query)
    {
        return $query->orderBy("ordering");
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

    public function getDisplayColorAttribute($val){
        return $val ?? $this->name;
    }
    #endregion

    #region relations
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
    #endregion

    #region helpers
    #endregion
}
