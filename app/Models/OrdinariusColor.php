<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrdinariusColor extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = "name";
    protected $keyType = "string";

    protected $fillable = [
        "name", "display_name",
        "display_color",
        "desc",
        "ordering",
    ];

    public function ordinarium(){
        return $this->hasMany(Ordinarius::class, "color_code", "name")
            ->orderByRaw(
                "case
                    when part regexp '^kyrie' then 1
                    when part regexp '^gloria' then 2
                    when part regexp '^psalm' then 3
                    when part regexp '^aklamacja' then 4
                    when part regexp '^sanctus' then 5
                    when part regexp '^agnus-dei' then 6
                else 99 end"
            );
    }

    public function getDisplayColorAttribute($val){
        return $val ?? $this->name;
    }

    #region helpers
    public static function ordered()
    {
        $ordered = collect(DB::select(<<<SQL
            with recursive ordered as (
                select oc.name, oc.ordering, 1 as lvl
                from ordinarius_colors oc
                where oc.name not in (select ordering from ordinarius_colors where ordering is not null)
                    union all
                select oc.name, oc.ordering, o.lvl + 1
                from ordered o
                    join ordinarius_colors oc on oc.name = o.ordering
            )
            select
                oc.name,
                coalesce(oc.display_name, oc.name) as display_name,
                coalesce(oc.display_color, oc.name) as display_color,
                oc.desc,
                o.lvl
            from ordinarius_colors oc
                join ordered o on o.name = oc.name
            order by lvl;
        SQL));

        return self::with("ordinarium")
            ->get()
            ->sortBy(fn ($oc) => $ordered->firstWhere("name", $oc->name)->lvl)
            ->values();
    }
    #endregion
}
