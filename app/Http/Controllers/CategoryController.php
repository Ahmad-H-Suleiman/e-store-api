<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index(){
        $categories=Category::all();
        return CategoryResource::collection($categories);
    }
    
    public function getCategoryProducts($category_id){
        $products=Category::findOrFail($category_id)->products()->where('is_available', 1)->paginate(50);
        return ProductResource::collection($products);
    }
}
