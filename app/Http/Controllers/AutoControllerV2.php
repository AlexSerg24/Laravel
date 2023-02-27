<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auto;

class AutoControllerV2 extends Controller
{
    public function filterAuto(Request $request) 
    {
        $query = Auto::query();

        if($request->ajax()){
            $autos = $query->where(['category'=>$request->auto_category])->get();
            return response()->json(['autos'=>$autos]);
        }

    }
}
