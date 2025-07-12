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

    #region attributes
    public function for_changes()
    {
        return [
            "name" => $this->name,
            "label" => $this->label,
            "before" => $this->before,
            "replace" => $this->replace,
        ];
    }
}
