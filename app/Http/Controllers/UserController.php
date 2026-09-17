<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Seller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(RegisterUserRequest $request){
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
            ]);

        return response()->json(['message'=>'created sucssesfully'], 201);
    }

    public function login(LoginUserRequest $request){

        if(!Auth::attempt($request->only('email', 'password')))
            return response()->json(['message'=>'invalid email or password'],401);

        $user=User::where('email', $request->email)->firstOrFail();
        $token=$user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=>'login sucssesfully',
            'token'=>$token
        ],200);
        
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message'=>'logout sucssesfully',
        ],200);
        
    }


    public function getUser(){
        $user=Auth::user();
        return response()->json($user, 200);
    }
    

    public function getUserOrders(){
        $orders=Auth::user()->orders;
        return response()->json($orders, 200);
    }
    
    
}
