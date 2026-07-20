<?php

namespace App\Scaffolds;

use App\Models\Formula;
use App\Models\OrdinariusColor;
use Wpwwhimself\Shipyard\Scaffolds\Modal as ShipyardModal;

class Modal extends ShipyardModal
{
    protected static function items(): array
    {
        return [
            "add-set" => [
                "heading" => "Utwórz nowy zestaw",
                "target_route" => "set-add",
                "fields" => [
                    [
                        "name" => "name",
                        "type" => "text",
                        "label" => "Nazwa zestawu",
                        "icon" => model_field_icon("sets", "name"),
                        "required" => true,
                    ],
                    [
                        "name" => "formula",
                        "type" => "select",
                        "label" => "Formuła",
                        "icon" => model_icon("formulas"),
                        "required" => true,
                        "extra" => [
                            "selectData" => [
                                "optionsFromScope" => [
                                    Formula::class,
                                    "forConnection",
                                    "option_label",
                                    "name",
                                ],
                            ],
                        ],
                    ],
                    [
                        "name" => "color",
                        "type" => "select",
                        "label" => "Kolor cz. st.",
                        "icon" => model_icon("ordinarius-colors"),
                        "required" => true,
                        "extra" => [
                            "selectData" => [
                                "optionsFromScope" => [
                                    OrdinariusColor::class,
                                    "forConnection",
                                    "option_label",
                                    "name",
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
