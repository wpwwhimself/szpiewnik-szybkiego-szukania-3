<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongNote extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "user_id",
        "content",
    ];

    #region relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function song()
    {
        return $this->belongsTo(Song::class, "title", "title");
    }
    #endregion
}
