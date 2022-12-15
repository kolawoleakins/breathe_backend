<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CloseAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function profile(){

        // $profile = User::where('id', Auth::id())->get();
        $profile = User::find(Auth::id());

        if($profile):
            return response()->json(["profile"=>$profile], 200);
        else:
            return response()->json(["message"=>"No Record Found"], 404);
        endif;  
     
    }

    public function updateprofile(Request $request){
        $data = $request->validate([
            "name" => 'required|string|max:191',
            "email"=> 'required|string|max:191',
            "phone"=> "required|string",
            "usertype"=> "required|string",
            "userlocation"=> "required|string",
            "state"=> "required|string",
            "facilityaddress"=> "required|string",
            "homeaddress"=> "required|string",
            "nin"=> "required|string",
        ]);

        $user = User::where('id', Auth::id())->update([
            "name"=>$data['name'],
            "email"=>$data['email'],
            "phone"=>$data['phone'],
            "usertype"=>$data['usertype'],
            "userlocation"=>$data['userlocation'],
            "state"=>$data['state'],
            "facilityaddress"=>$data['facilityaddress'],
            "homeaddress"=>$data['homeaddress'],
            "nin"=>$data['nin'],
        ]);


        $response = [
            'message' => "Details Updated Successfully"
        ];

        return response($response, 201);


    }

    public function updatebvn(Request $request){
        $data = $request->validate([
            "bvn"=> "required|string",
        ]);

        $user = User::where('id', Auth::id())->update([
            "bvn"=>$data['bvn']
        ]);

        $response = [
            'message' => "Bvn Updated Successfully"
        ];

        return response($response, 201);
    }


    public function updateid(Request $request){
        $data = $request->validate([
            "facilityid"=> "required|mimes:jpg,jpeg,png",
            "regulatoryid"=> "required|mimes:jpg,jpeg,png",
        ]);

        
        $facilityid = time().'.'.$request->facilityid->extension();  
        $request->facilityid->move(public_path('uploads'), $facilityid);

        $regulatoryid = time().'.'.$request->regulatoryid->extension();  
        $request->regulatoryid->move(public_path('uploads'), $regulatoryid);

        $user = User::where('id', Auth::id())->update([
            "facilityid"=>$facilityid,
            "regulatoryid"=>$regulatoryid,
        ]);

        $response = [
            'message' => "Details Updated Successfully"
        ];

        return response($response, 201);
    }

    public function updatepassword(Request $request){
        $data = $request->validate([
            "password"=> "required|string|confirmed|min:6",
        ]);

        $user = User::where('id', Auth::id())->update([
            "password"=>Hash::make($data['password']),
        ]);

        $response = [
            'message' => "Password Updated Successfully"
        ];
        return response($response, 201);
    }

    public function closeaccount(Request $request){
        $data = $request->validate([
            "reason"=> "required|string",
        ]);

        $account = User::where('id', Auth::id())->first();

        $email = $account->email;

        CloseAccount::create([
            "reason"=>$data['reason'],
            "email"=>$email,
            "userid"=>Auth::id()
        ]);

        $response = [
            'message' => "Account Closing Request Sent"
        ];

        return response($response, 201);
    }




}
