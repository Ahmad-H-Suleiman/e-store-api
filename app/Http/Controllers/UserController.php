<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\Seller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getUser(){
        $user=Auth::user();
        return new UserResource($user);
    }
    

    public function getUserOrders(){
        $orders=Auth::user()->orders()->with('products.product')->paginate(10);
        return OrderResource::collection($orders);
    }
    
    
}
