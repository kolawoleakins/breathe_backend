<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VoucherModel;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(){
        $voucher = VoucherModel::all();
        return response()->json(["voucher"=>$voucher], 200);
    }

    public function show($id){
        $voucher = VoucherModel::find($id);
        if($voucher):
            return response()->json(["voucher"=>$voucher], 200);
        else:
            return response()->json(["message"=>"No Record Found"], 404);
        endif;        
    
    }

    public function store(Request $request){

        //validation
        $request->validate([
            'amount'=>'required|numeric'
        ]);
        
        $product  = new VoucherModel();
        $product->amount = $request->amount;

        //save
        $product->save();

        //send response 
        return response()->json(["message"=>"Voucher added successfully"]);
    }

    public function update(Request $request, $id){
        //validation
        $request->validate([
            'amount'=>'required|numeric'
        ]);

        $voucher  = VoucherModel::where('id', $id)->update([
            "amount"=>$request->amount
        ]);
    
        
        if($voucher):
            return response()->json(["message"=>"Voucher updated successfully"]);

        else:

            return response()->json(["message"=>"No Voucher Found"]);

        endif;

    }

    public function destroy($id){
        $voucher = VoucherModel::find($id);

        if($voucher){
            $voucher->delete();
            return response()->json(["message"=>"Voucher deleted successfully"]);
        }else{
            return response()->json(["message"=>"Voucher not found"]);
        }
        
    }
}
