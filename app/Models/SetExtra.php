<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetExtra extends Model
{
    use HasFactory;

    protected $fillables = [
        "set_id",
        "name", "before", "replace",
    ];
}
