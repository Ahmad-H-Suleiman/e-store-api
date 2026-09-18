<?php

namespace App\Http\Controllers;

use App\Exceptions\ProductUnavailableException;
use App\Exceptions\UnauthorizedActionException;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProducRequest;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class ProductController extends Controller
{

    public function index(){
        $products=Product::where('is_available',1)->get();

        return response()->json($products,200);
    }

    public function show($product_id){
        $product=Product::findOrFail($product_id);

        if($product->is_available==0){
            throw new ProductUnavailableException('product is not available');
        }

        return response()->json($product, 200);
    }

    public function store(StoreProductRequest $request){
        $seller=Auth::user()->seller;
        $seller_id=$seller->id;
        $validated_data=$request->validated();
        $validated_data['seller_id']=$seller_id;

        
            if($request->hasFile('image')){
                $path=request()->file('image')->store($seller->user->name.' products', 'public');
                $validated_data['image']=$path;
            }
            Product::create($validated_data);

            return response()->json(['message'=>'created sucssesfully'], 201);

    }

    public function update(UpdateProducRequest $request, $product_id){

        $product=Product::findOrFail($product_id);
        $validated_data=$request->validated();

        if($product->seller->user_id != Auth::user()->id){
            throw new UnauthorizedActionException('you are not authorized to modify this product');
        }

        if($product->is_available==0){
            throw new ProductUnavailableException('product is not available');
        }


        $oldImage=null;
        if($request->hasFile('image')){
            $oldImage=$product->image;
            $newImage=$request->file('image')->store($product->seller->user->name . ' products', 'public');
            $validated_data['image']=$newImage;
        }

        $product->update($validated_data);
        if($oldImage){
            Storage::disk('public')->delete($oldImage);
        }

        return response()->json(['message'=>'updated sucssesfully'], 200);

    }

    public function destroy($product_id){
        $product=Product::findOrFail($product_id);

        if($product->seller->user_id != Auth::user()->id){
            throw new UnauthorizedActionException('you are not authorized to delete this product');
        }

        if($product->is_available==0){
            throw new ProductUnavailableException('product is not available');
        }
        
        $product->update(['is_available'=>0]);
        
        return response()->json(null,204);
    }

    public function addCtegoriesToProduct(Request $request,$product_id){
        $product=Product::findOrFail($product_id);

        if(Auth::user()->seller->id != $product->seller_id){
            throw new UnauthorizedActionException('you are not authorized to modify this product');
        }

        if($product->is_available==0){
            throw new ProductUnavailableException('product is not available');
        }

        $product->categories()->syncWithoutDetaching($request->category_id);
        return response()->json('category attached',201);
    }
    public function getProductCategories($product_id){
        $product=Product::findOrFail($product_id);

        if($product->is_available==0){
            throw new ProductUnavailableException('product is not available');
        }

        $categories=$product->categories;
        return response()->json($categories,200);
    }

}
