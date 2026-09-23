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

    public function getUser(){
        $user=Auth::user();
        return response()->json($user, 200);
    }
    

    public function getUserOrders(){
        $orders=Auth::user()->orders;
        return response()->json($orders, 200);
    }
    
    
}
