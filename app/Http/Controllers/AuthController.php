<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request){
        $user= User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
            ]);

            $token=$user->createToken('auth_token')->plainTextToken;

            $user->sendEmailVerificationNotification();

        return response()->json(['message'=>'created sucssesfully now verify you email to login', 'token'=>$token], 201);
    }

    public function login(LoginUserRequest $request){

        if(!Auth::attempt($request->only('email', 'password')))
            return response()->json(['message'=>'invalid email or password'],401);        

        $user=User::where('email', $request->email)->firstOrFail();

        if (!$user->hasVerifiedEmail()){
            return response()->json(['message'=>'please verify your email pefor log in'], 403);
        }
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

    public function resrndVerification(Request $request){
        $request->validate(['email'=>'required|email|exists:users,email']);
        $user=User::where('email', $request->email)->firstOrFail();

        if ($user->hasVerifiedEmail()){
            return response()->json(['message'=>'your email is verifyed login please'], 400);
        }

        $user->sendEmailVerificationNotification();
        $token=$user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=>'the verify email has been sent',
            'token'=>$token,
        ],200);

    } 
}
