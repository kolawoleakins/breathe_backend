<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
            $token = $user->createToken('MultiTenant')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response($response, 200);
        }


    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response(["message"=>"Logged out successfully"]);
    }

}
