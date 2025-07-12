<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "changeable_type", "changeable_id",
        "action", "details",
    ];

    public $timestamps = false;

    protected $casts = [
        "details" => "collection",
        "date" => "datetime",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function changeable()
    {
        return $this->morphTo();
    }
}
