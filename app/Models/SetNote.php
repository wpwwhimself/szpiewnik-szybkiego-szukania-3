<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetNote extends Model
{
    use HasFactory;

    protected $fillable = [
        "set_id", "user_id",
        "element_code", "content",
    ];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
