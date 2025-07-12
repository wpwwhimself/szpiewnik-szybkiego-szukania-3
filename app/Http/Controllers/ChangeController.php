<?php

namespace App\Http\Controllers;

use App\Models\Change;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChangeController extends Controller
{
    public function list(string $type, string|int $id)
    {
        if (auth()->user()->clearance_id < 4) abort(403);

        $model = "App\\Models\\" . Str::studly($type);
        $item = $model::find($id);

        return view("changes.list", compact(
            "item",
        ));
    }

    #region helpers
    public static function add($model, $action, $old_data = null, $relations_to_track = [])
    {
        $new_data = $model->getChanges();
        foreach ($relations_to_track as $rel) {
            $new_data[$rel] = $model->fresh()->{$rel};
        }

        $model->changes()->create([
            "user_id" => Auth::id(),
            "action" => $action,
            "details" => collect($new_data)
                ->map(fn ($nval, $fld) => [$old_data[$fld], $nval])
                ->filter(fn ($vals, $fld) => (in_array($fld, $relations_to_track))
                    ? $vals[1]->diffAssoc($vals[0])->isNotEmpty()
                    : $vals[0] != $vals[1]
                )
                ->map(fn ($vals, $fld) => (in_array($fld, $relations_to_track))
                    ? array_map(fn ($val) => $val->for_changes, $vals)
                    : $vals
                )
                ->except(["created_at", "updated_at"]),
        ]);
    }

    public static function updateWithChange($model, $action, $callback, $relations_to_track = [])
    {
        $old_data = $model->getOriginal();
        foreach ($relations_to_track as $rel) {
            $old_data[$rel] = $model->{$rel};
        }

        $callback();

        self::add($model, $action, $old_data, $relations_to_track);
    }
    #endregion
}
