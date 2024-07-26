<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
    public function test(){
        return response()->json([
            'message'=>'test22',
            'status'=>200
        ]);
    }
}
