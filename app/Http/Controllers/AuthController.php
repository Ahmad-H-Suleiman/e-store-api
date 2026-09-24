<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request){
        $user= User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
            ]);

            $token=$user->createToken('registeration_token')->plainTextToken;

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
        $token=$user->createToken('registeration_token')->plainTextToken;

        return response()->json([
            'message'=>'the verify email has been sent',
            'token'=>$token,
        ],200);

    } 

    public function forgotPassword(ForgetPasswordRequest $request){
        $status=Password::sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT){
            return response()->json(['message'=>'Password reset link sent successfully'], 200);
        }

        if ($status === Password::RESET_THROTTLED){
            return response()->json(['message'=>'password rest email has been sent blease wait if you need another one'], 429);
        }

        return response()->json(['message'=>'some thing went rong'], 400);
    }

    public function resetPassword(ResetPasswordRequest $request){
        $status=Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password){
            $user->forceFill(['password'=>Hash::make($password),])->setRememberToken(Str::random(60));
            $user->save();
            $user->tokens()->delete();
            event(new PasswordReset($user));
        });

        if($status === Password::PASSWORD_RESET){
            return response()->json(['message'=>'Password reset successfully'], 200);
        }

        return response()->json(['message'=>'Invalid or expired password token'], 400);

    }


}
