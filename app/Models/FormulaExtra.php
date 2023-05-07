<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulaExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        "formula",
        "name", "before", "replace",
    ];
}
