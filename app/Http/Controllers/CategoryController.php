<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index(){
        $categories=Category::all();
        return response()->json($categories, 200);
    }
    
    public function getCategoryProducts($category_id){
        $products=Category::findOrFail($category_id)->products;
        return response()->json($products,200);
    }
}
