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
            get: fn () => $this->__toString(),
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
    public static function ordered()
    {
        $ordered = collect(DB::select(<<<SQL
            with recursive ordered as (
                select oc.name, oc.ordering, 1 as lvl
                from ordinarius_colors oc
                where oc.name not in (select ordering from ordinarius_colors where ordering is not null)
                    union all
                select oc.name, oc.ordering, o.lvl + 1
                from ordered o
                    join ordinarius_colors oc on oc.name = o.ordering
            )
            select
                oc.name,
                coalesce(oc.display_name, oc.name) as display_name,
                coalesce(oc.display_color, oc.name) as display_color,
                oc.desc,
                o.lvl
            from ordinarius_colors oc
                join ordered o on o.name = oc.name
            order by lvl;
        SQL));

        return self::with("ordinarium")
            ->get()
            ->sortBy(fn ($oc) => $ordered->firstWhere("name", $oc->name)->lvl)
            ->values();
    }
    #endregion
}
