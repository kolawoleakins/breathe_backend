<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Products::all();
        return response()->json(["products"=>$products], 200);
    }

    public function show($id){
        $products = Products::find($id);
        if($products):
            return response()->json(["products"=>$products], 200);
        else:
            return response()->json(["message"=>"No Record Found"], 404);
        endif;        
    
    }

    public function store(Request $request){

        //validation
        $request->validate([
            'name'=>'required|max:191',
            'short_name'=>'required|max:191',
            'price'=>'required|max:191',
            'image'=>'required|mimes:jpg,jpeg,png',
        ]);

        $image = time().'.'.$request->image->extension();  
        $request->image->move(public_path('uploads'), $image);

        
        $product  = new Products();
        $product->name = $request->name;
        $product->short_name = $request->short_name;
        $product->image = $request->image;
        $product->price = $request->price;

        //save
        $product->save();

        //send response 
        return response()->json(["message"=>"Product added successfully"]);
    }

    public function update(Request $request, $id){
        //validation
        $request->validate([
            'name'=>'required|max:191',
            'short_name'=>'required|max:191',
            'price'=>'required|max:191',
            'image'=>'required|mimes:jpg,jpeg,png',
        ]);

        
        $image = time().'.'.$request->image->extension();  
        $request->image->move(public_path('uploads'), $image);
        
        $product  = Products::find($id);
        if($product):
            $product  = new Products();
            $product->name = $request->name;
            $product->short_name = $request->short_name;
            $product->image = $request->image;
            $product->price = $request->price;

            //update instead of save
            $product->update();
            return response()->json(["message"=>"Product updated successfully"]);

        else:

            return response()->json(["message"=>"No Product Found"]);

        endif;

    }

    public function destroy($id){
        $product = Products::find($id);

        if($product){
            $product->delete();
            return response()->json(["message"=>"Product deleted successfully"]);
        }else{
            return response()->json(["message"=>"Product not found"]);
        }
        
    }


}
