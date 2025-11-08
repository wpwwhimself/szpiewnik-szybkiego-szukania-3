<?php

namespace App\Models;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\ComponentAttributeBag;

class SetNote extends Model
{
    use HasFactory;

    public const META = [
        "label" => "Notatki do zestawów",
        "icon" => "note",
        "description" => "",
        "role" => "technical",
        "ordering" => 23,
    ];

    protected $fillable = [
        "set_id", "user_id",
        "element_code", "content",
    ];

    #region presentation
    public function __toString(): string
    {
        return implode(" ", [
            $this->user->name,
            "dla",
            $this->set->name,
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
    #endregion

    #region relations
    public function set()
    {
        return $this->belongsTo(Set::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    #endregion

    #region helpers
    #endregion
}
