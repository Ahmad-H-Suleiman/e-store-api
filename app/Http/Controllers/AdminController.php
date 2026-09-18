<?php

namespace App\Http\Controllers;

use App\Exceptions\ProductUnavailableException;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Storage;

class AdminController extends Controller
{
    public function getAllSellers(){
        $sellers=Seller::all();
        return response()->json($sellers, 200);
    }

    public function getAllUsers(){
        $users=User::all();
        return response()->json($users, 200);
    }

    public function deleteProduct($product_id){
        $product=Product::findOrFail($product_id);

        if($product->is_available==0){
            throw new ProductUnavailableException('product is not available'); 
        }
        
        $product->update(['is_available'=>0]);

        return response()->json(null,204);
        
    }

    // public function deleteSeller($seller_id){
    //     $seller=Seller::findOrFail($seller_id);
    //     $user_id=$seller->user->id;
    //     $image=$seller->image;

    //     DB::transaction(function() use($user_id, $seller){
    //         $seller->delete();
    //         $user=User::findOrFail($user_id);
    //         $user->update(['role'=>'user']);
           
    //     });

    //     Storage::disk('public')->delete($image);
        
    //     return response()->json(null,204);
    // }

    public function getAllOrders(){
        $orders=Order::all();
        return response()->json($orders, 200);
    }

    public function updateOrderStatus(Request $request, $order_id){
        $request->validate(['status'=>'required|string|in:processing,confirmed,completed,cancelled']);
        $order=Order::findOrFail($order_id);
        
        $order->update(['status'=>$request->status]);
        
        return response()->json(['message'=>'updated sucssessfully'], 200);
    }

    public function getUnavalableProducts(){
        $products=Product::where('is_available',0)->get();

        return response()->json($products,200);
    }

    public function getProduct($product_id){

        $product=Product::findOrFail($product_id);

        return response()->json($product,200);
    }

}
