<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulaExtra extends Model
{
    use HasFactory;

    protected $table = "formula_extras";

    protected $fillables = [
        "formula",
        "name", "before", "replace",
    ];
}
