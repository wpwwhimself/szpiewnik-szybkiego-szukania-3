<?php

namespace App\Models;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\ComponentAttributeBag;

class Set extends Model
{
    use HasFactory;

    public const META = [
        "label" => "Zestawy",
        "icon" => "tray-full",
        "description" => "Zestawy pieśni i psalmów na dany dzień.",
        "role" => "set-manager",
        "ordering" => 21,
    ];

    protected $fillable = [
        "name", "formula", "color",
        "user_id", "public",
        "sIntro", "sOffer", "sCommunion", "sAdoration", "sDismissal",
        "pPsalm", "pAccl",
    ];

    #region presentation
    public function __toString(): string
    {
        return $this->name;
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
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
                "slot" => $this->name,
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

    public function getAllSongsAttribute(){
        $songs = [
            $this->sIntro ? preg_split("/\r?\n/", $this->sIntro) : null,
            $this->sOffer ? preg_split("/\r?\n/", $this->sOffer) : null,
            $this->sCommunion ? preg_split("/\r?\n/", $this->sCommunion) : null,
            $this->sAdoration ? preg_split("/\r?\n/", $this->sAdoration) : null,
            $this->sDismissal ? preg_split("/\r?\n/", $this->sDismissal) : null,
        ];
        return $songs;
    }
    #endregion

    #region relations
    public function formulaData(){
        return $this->belongsTo(Formula::class, "formula", "name");
    }
    public function colorData(){
        return $this->hasOne(OrdinariusColor::class, "name", "color");
    }
    public function extras(){
        return $this->hasMany(SetExtra::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function changes()
    {
        return $this->morphMany(Change::class, "changeable")->orderByDesc("date");
    }
    public function notes()
    {
        return $this->hasMany(SetNote::class);
    }
    public function notesForCurrentUser()
    {
        return $this->hasMany(SetNote::class)->where("user_id", Auth::id());
    }
    #endregion

    #region helpers
    #endregion
}
