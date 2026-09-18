<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedActionException;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public function index(){
        $seller=Auth::user()->seller;
        return response()->json($seller,200);
    }

    public function getSellerInfo($seller_id){
        $seller=Seller::findOrFail($seller_id);
        return response()->json($seller,200);
    }


    public function store(StoreSellerRequest $request){
        $user_id=Auth::user()->id;
        $validated_data=$request->validated();
        $validated_data['user_id']=$user_id;

        DB::transaction(function() use($validated_data, $user_id, $request){

            $user=User::findOrFail($user_id);
        
            if($request->hasFile('image')){
                $path=request()->file('image')->store('seller','public');
                $validated_data['image']=$path;
            }
            Seller::create($validated_data);

            $user->update(['role'=>'seller']);

        });

        return response()->json(['message'=>'created sucssesfully'],  201);

    }

    public function update(UpdateSellerRequest $request){

        $user_id=Auth::user()->id;
        $validated_data=$request->validated();
        $seller=Seller::where('user_id', $user_id)->firstOrFail();
        $oldImage=null;
        if($request->hasFile('image')){
            $oldImage=$seller->image;
            $newImage=$request->file('image')->store('seller', 'public');
            $validated_data['image']=$newImage;
        }

        $seller->update($validated_data);
        if($oldImage){
            Storage::disk('public')->delete($oldImage);
        }

        return response()->json(['message'=>'updated sucssesfully'], 200);
    }

    // public function destroy(){
    //     $user_id=Auth::user()->id;
    //     $seller=Seller::where('user_id', $user_id)->firstOrFail();
    //     $image=$seller->image;
    //     DB::transaction(function() use($user_id, $seller){
    //         $seller->delete();
    //         $user=User::findOrFail($user_id);
    //         $user->update(['role'=>'user']);
           
    //     });

    //     Storage::disk('public')->delete($image);
        
    //     return response()->json(null,204);
    // }


    public function getSellerProducts(){
        $products=Auth::user()->seller->products;
        return response()->json($products, 200);
    }

    public function updateProductStatus($product_id){
        $product=Product::findOrFail($product_id);

        if($product->seller->user_id != Auth::user()->id){
            throw new UnauthorizedActionException('you are not authorized to modify this product');
        }

        if($product->is_available==1){
            return response()->json(['message'=>'this product is oredy available'],200);
        }
        
        $product->update(['is_available'=>1]);
        
        return response()->json(['message'=>'updated sucssesfully'],200);
    }

}
