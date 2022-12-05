<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Delivery;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request){

        //validation
        // $request->validate([
        //     'name'=>'required|max:191',
        //     'short_name'=>'required|max:191',
        //     'price'=>'required|max:191',
        //     'image'=>'required|mimes:jpg,jpeg,png',
        // ]);

        $items = json_decode($request->getContent(), true);

        foreach( $items as $item ){
            $orderItem = new Checkout();
            if(!isset($item['orderid']) || empty($item['orderid'])){
                $orderItem->orderid = uniqid();
            }else{
                $orderItem->orderid = $item['orderid'];
            }
            $orderItem->userid = Auth::user()->id;
            $orderItem->address = $item['address'];
            $orderItem->phone = $item['phone'];
            $orderItem->item_id = $item['item_id'];
            $orderItem->item_name = $item['item_name'];
            $orderItem->price = $item['price'];
            $orderItem->preference = $item['preference'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->save();
        }

        return response()->json(["message"=>"Item Added Successfully","status"=>100]);
        // return response()->json($request->getContent());


    }

    public function checkpayment(Request $request){

        // validation
        $request->validate([
            'orderid'=>'required|max:191',
            'transaction_reference'=>'required|max:191',
            'payment_method'=>'required|max:191'
        ]);

        $item = Checkout::where('orderid', $request->orderid)->first();
        $address = Checkout::where('orderid', $request->orderid)->first();

        //update checkout

        if($item){
            if($item->status == "pending"){
                Checkout::where('orderid', $request->orderid)->update([
                    "is_paid"=>true,
                    "transaction_reference"=>$request->transaction_reference,
                    "payment_method"=>$request->payment_method,
                    "status"=>"processing"
                ]);
        
                //add to delivery
                Delivery::create([
                    "userid"=>Auth::user()->id,
                    "orderid"=>$request->orderid,
                    "address"=>$address->address,
                    "orderdate"=>Date('y/m/d H-I-s'),
                ]);
        
                //add notification
                Notifications::create([
                    "userid"=>Auth::user()->id,
                    "orderid"=>$request->orderid,
                    "type"=>"placed order",
                    "message"=>"Your order has been placed successfully"
                ]);
                return response()->json(["message"=>"Successful", "status"=>100]);
            }else{
                return response()->json(
                    ["message"=>"Failed, order doesn't exist or has been placed already", 
                        "status"=>0]);
            }
        }else{
            return response()->json(["message"=>"Failed, order doesn't exist", "status"=>0]);
        }

    }

}
