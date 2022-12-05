<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function arrival(Request $request){
        
        $arrival = Delivery::where('userid', Auth::id())
        ->where('orderid',$request->orderid)->get();

        if($arrival):
            return response()->json(["arrival"=>$arrival], 200);
        else:
            return response()->json(["message"=>"No Record Found"], 404);
        endif;        
    
    }
}
