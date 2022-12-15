<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function fundwallet(Request $request){
        //validation
        $request->validate([
            'amount'=>'required|numeric'
        ]);

        $key = Wallet::where('userid', Auth::id())->first();

        if(empty($key->balance)){
            $wallet  = Wallet::where('userid', Auth::id())->update([
                "balance"=>$request->amount
            ]);
        
        }else{
            $wallet  = Wallet::where('userid', Auth::id())->update([
                "balance"=>$request->amount + $key->balance
            ]);
        
        }

       
        
        if($wallet):
            return response()->json(["message"=>"Wallet Funded successfully"]);

        else:

            return response()->json(["message"=>"No Voucher Found"]);

        endif;

    }

    public function viewwallet(){
        $wallet  = Wallet::where('userid', Auth::id())->get();    
        
        if($wallet):
            return response()->json(["Wallet"=>$wallet]);

        else:

            return response()->json(["message"=>"No wallet Found"]);

        endif;

    }
}
