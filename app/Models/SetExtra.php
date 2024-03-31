<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        "set_id",
        "name", "label", "before", "replace",
    ];
}
