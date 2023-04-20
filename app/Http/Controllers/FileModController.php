<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileModController extends Controller
{
    public function dataChange(Request $rq){
        $path = resource_path()."/js/data/".$rq->fileToChange.".json";
        $data = json_decode(File::get($path), true);
        if($rq->item_id !== null){
            $data[$rq->item_id] = $rq->itemToChange;
        }else{
            $data[] = $rq->itemToChange;
        }
        File::put($path, json_encode($data, JSON_PRETTY_PRINT));
        return response()->json([
            "status" => "all's cool bro, file updated",
            "changingTo" => $rq->all(),
            "effect" => $data,
        ]);
    }
}
