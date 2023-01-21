<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\PasswordVerify;
use App\Models\User;
use App\Models\VerificationCodes;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            "name" => 'required|string|max:191',
            "email"=> 'required|string|max:191|unique:users,email',
            "phone"=> "required|string",
            "usertype"=> "required|string",
            "userlocation"=> "required|string",
            "state"=> "required|string",
            "facilityaddress"=> "required|string",
            "homeaddress"=> "required|string",
            // "facilityid"=> "required|mimes:jpg,jpeg,png",
            // "regulatoryid"=> "required|mimes:jpg,jpeg,png",
            // "bvn"=> "required|string",
            "nin"=> "required|string",
            "password"=> "required|string|confirmed|min:6",
        ]);


        $bvn = "";
        if(!isset($request->bvn) || empty($request->bvn)){
            $bvn = "";
        }else{
            $bvn = $request->bvn;
        }

        if(!empty($request->facilityid)){
            $facilityid = time().'.'.$request->facilityid->extension();  
            $request->facilityid->move(public_path('uploads'), $facilityid);
        }else{
            $facilityid = "";
        }
        
        if(!empty($request->regulatoryid)){
            $regulatoryid = time().'.'.$request->regulatoryid->extension();  
            $request->regulatoryid->move(public_path('uploads'), $regulatoryid);
        }else{
            $regulatoryid = "";
        }
        

        // $regulatoryid = time().'.'.$request->regulatoryid->extension();  
        // $request->regulatoryid->move(public_path('uploads'), $regulatoryid);


        // $user = User::create([
        //     "name"=>$data['name'],
        //     "email"=>$data['email'],
        //     "phone"=>$data['phone'],
        //     "usertype"=>$data['usertype'],
        //     "userlocation"=>$data['userlocation'],
        //     "state"=>$data['state'],
        //     "facilityaddress"=>$data['facilityaddress'],
        //     "homeaddress"=>$data['homeaddress'],
        //     "facilityid"=>$facilityid,
        //     "regulatoryid"=>$regulatoryid,
        //     "bvn"=>$bvn,
        //     "nin"=>$data['nin'],
        //     "password"=>Hash::make($data['password']),
        // ]);

        $user = DB::table('users')->insertGetId([
            "name"=>$data['name'],
            "email"=>$data['email'],
            "phone"=>$data['phone'],
            "usertype"=>$data['usertype'],
            "userlocation"=>$data['userlocation'],
            "state"=>$data['state'],
            "facilityaddress"=>$data['facilityaddress'],
            "homeaddress"=>$data['homeaddress'],
            "facilityid"=>$facilityid,
            "regulatoryid"=>$regulatoryid,
            "bvn"=>$bvn,
            "nin"=>$data['nin'],
            "password"=>Hash::make($data['password']),
        ]);

        Wallet::create([
            'userid'=>$user,
            'balance'=>0,
            
        ]);


        // $token = $user->createToken('Breet')->plainTextToken;

        $response = [
            'message' => "Registered Successfully",
            // 'token' => $token
        ];

        return response($response, 201);


    }

    public function login(Request $request){
        $data = $request->validate([
            "email"=> 'required|string|max:191',
            "password"=> "required|string",
        ]);

        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password) ){
            return response(["message"=>"Invalid credentials"], 401);
        }else{
            //create token
            $token = $user->createToken('Breet')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response($response, 200);
        }


    }

    public function passwordreset(Request $request){

        $request->validate([
            'email' => 'required|email',
        ]);

        //generated code
        $code = rand(100000, 900000);

        $data = [
            'code' => $code,
            'email' => $request->email,
        ];

        // dd($data); preview data that was sent

        Mail::to($request->email)->send(new PasswordVerify($data));

        $response = [
            'message' => "Verification code sent successfully"
        ];

        return response($response, 200);

        //  return response(["message"=>"Invalid credentials"], 401);

    }

    public function updatepassword(Request $request){
        $data = $request->validate([
            "password"=> "required|string|confirmed|min:6",
            "code"=> "required|string",
        ]);

        $getCode = VerificationCodes::where('email', $data['email'])->first();

        if($getCode->code == $data['code']){
            User::where('email', $data['email'])->update([
                "password"=>Hash::make($data['password'])
            ]);

            $response = [
                'message' => "Password updated successfully"
            ];

            return response($response, 200);
        }else{
            return response(["message"=>"Invalid credentials"], 401);
        }


    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response(["message"=>"Logged out successfully"]);
    }


    ###admin section
    public function createteam(Request $request){
        $data = $request->validate([
            "name" => 'required|string|max:191',
            "email"=> 'required|string|max:191|unique:users,email',
            "phone"=> "required|string",
            "userlocation"=> "required|string",
            "state"=> "required|string",
            "password"=> "required|string|confirmed|min:6",
        ]);


        $bvn = "";

        if(!empty($request->facilityid)){
            $facilityid = time().'.'.$request->facilityid->extension();  
            $request->facilityid->move(public_path('uploads'), $facilityid);
        }else{
            $facilityid = "";
        }
        
        
        if(!empty($request->regulatoryid)){
            $regulatoryid = time().'.'.$request->regulatoryid->extension();  
            $request->regulatoryid->move(public_path('uploads'), $regulatoryid);
        }else{
            $regulatoryid = "";
        }
        

        $user = DB::table('users')->insertGetId([
            "name"=>$data['name'],
            "email"=>$data['email'],
            "phone"=>$data['phone'],
            "usertype"=>"team",
            "userlocation"=>$data['userlocation'],
            "state"=>$data['state'],
            "facilityaddress"=>"nill",
            "homeaddress"=>"nill",
            "facilityid"=>$facilityid,
            "regulatoryid"=>$regulatoryid,
            "bvn"=>$bvn,
            "nin"=>"nill",
            "user_is"=>"team",
            "password"=>Hash::make($data['password']),
        ]);

        Wallet::create([
            'userid'=>$user,
            'balance'=>0,
            
        ]);


        // $token = $user->createToken('Breet')->plainTextToken;

        $response = [
            'message' => "Team created successfully",
            // 'token' => $token
        ];

        return response($response, 201);
    }

    public function viewteam(){
        if(Auth::user()->user_is == "admin"){
            $teams = User::where('user_is',"team")->get();
            if($teams):
                return response()->json(["team"=>$teams], 200);
            else:
                return response()->json(["message"=>"No Record Found"], 404);
            endif;  
        }else{
            return response()->json(["message"=>"Error"], 401);
        } 
    }

}
