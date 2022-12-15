<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function history(){
        
        // $orders = Checkout::select('orderid','price')->where('userid', Auth::id())->distinct()->get();
        // $orders = Checkout::select('orderid','price')->where('userid', Auth::id())->distinct()->get();
        // $amount = Checkout::where('userid', Auth::id())->where('orderid', $orders)->sum('price');
        // $orders = Checkout::select('price')->where('userid', Auth::id())->distinct()->get();

        $orders = DB::table('checkouts')
        ->selectRaw('orderid, SUM(price) AS total')
        ->groupBy('orderid')
        ->get();

        if($orders):
            return response()->json(["orders"=>$orders], 200);
        else:
            return response()->json(["message"=>"No Record Found"], 404);
        endif;       
    
    }
}
