<?php

namespace App\Http\Controllers;

use App\Exceptions\ProductUnavailableException;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SellerResource;
use App\Http\Resources\UserResource;
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
        $sellers=User::with('seller')->where('role','seller')->paginate(50);
        return UserResource::collection($sellers);
    }

    public function getAllUsers(){
        $users=User::paginate(50);
        return UserResource::collection($users);        
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
        $orders=Order::with('products.product')->paginate(50);
        return OrderResource::collection($orders);
    }

    public function updateOrderStatus(Request $request, $order_id){
        $request->validate(['status'=>'required|string|in:processing,confirmed,completed,cancelled']);
        $order=Order::with('products.product')->findOrFail($order_id);
        
        $order->update(['status'=>$request->status]);
        
        return new OrderResource($order);
    }

    public function getUnavalableProducts(){
        $products=Product::where('is_available',0)->paginate(50);

        return ProductResource::collection($products);
    }

    public function getProduct($product_id){

        $product=Product::with('seller.user')->findOrFail($product_id);

        return new ProductResource($product);
    }

}
