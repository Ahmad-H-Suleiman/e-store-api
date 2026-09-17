<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function Store(StoreOrderRequest $request){
        $user_id=Auth::user()->id;
        $validated_data=$request->validated();
        $validated_data['user_id']=$user_id;
        $total=0;

        DB::transaction(function() use($validated_data, $total){

            $order=Order::create($validated_data);

            foreach($validated_data['products'] as $p){
                $product=Product::findOrFail($p['product_id']);
                $subtotal=$product->price * $p['quantity'];

                $order->products()->create([
                    'product_id'=>$product->id,
                    'price'=>$product->price,
                    'quantity'=>$p['quantity'],
                    'subtotal'=>$subtotal,
                ]);
                $total +=$subtotal;
            }

            $order->update(['total'=>$total]);

        });

        return response()->json(['message'=>'created sucssessfully'],201);
    }

    public function update($order_id){
        $order=Order::findOrFail($order_id);
        
        if($order->user_id != Auth::user()->id){
            return response()->json(['message'=>'anuth', 403]);
        }

        if($order->status != "processing"){
            return response()->json(['message'=>'you cant canceled order after confirmed', 403]);
        }

        $order->update(['status'=>'cancelled']);
        
        return response()->json(['message'=>'updated sucssessfully'], 201);
    }

    
}
