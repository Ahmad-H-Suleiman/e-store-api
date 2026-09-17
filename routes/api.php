<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');

Route::get('product', [ProductController::class, 'index']);
Route::get('product/{droduct_id}', [ProductController::class, 'show']);
Route::get('seller/{seller_id}', [SellerController::class, 'getSellerInfo']);

Route::middleware('auth:sanctum')->group(function(){

    Route::get('user', [UserController::class, 'getUser']);
    Route::get('user/orders', [UserController::class, 'getUserOrders']);


    Route::get('seller', [SellerController::class, 'index']);
    Route::post('seller', [SellerController::class, 'store']);



    Route::get('product/{product_id}/categories',[ProductController::class, 'getProductCategories']);
    Route::get('category/{category_id}/products',[CategoryController::class, 'getCategoryProducts']);


    Route::post('order', [OrderController::class, 'store']);
    Route::put('order/{order_id}', [OrderController::class, 'update']);

});



Route::middleware(['auth:sanctum', 'seller'])->group(function(){

    Route::put('seller', [SellerController::class, 'update']);
    // Route::delete('seller', [SellerController::class, 'destroy']);

    Route::get('seller/products', [SellerController::class, 'getSellerProducts']);

    Route::post('product', [ProductController::class, 'store']);
    Route::put('product/{product_id}', [ProductController::class, 'update']);
    Route::delete('product/{product_id}', [ProductController::class, 'destroy']);
    Route::put('product/return/{product_id}', [SellerController::class, 'updateProductStatus']);

    Route::post('product/{product_id}/categories',[ProductController::class, 'addCtegoriesToProduct']);


});

Route::middleware(['auth:sanctum', 'admin'])->group(function(){
    Route::get('admin/users', [AdminController::class, 'getAllUsers']);
    Route::get('admin/sellers', [AdminController::class, 'getAllSellers']);
    Route::get('admin/orders', [AdminController::class, 'getAllOrders']);
    Route::get('admin/unavailableProduct', [AdminController::class, 'getUnavalableProducts']);
    Route::get('admin/product', [AdminController::class, 'getProduct']);

    Route::put('admin/{order_id}/order', [AdminController::class, 'updateOrderStatus']);

    // Route::delete('admin/{seller_id}/seller', [AdminController::class, 'deleteSeller']);
    Route::delete('admin/{product_id}/product', [AdminController::class, 'deleteProduct']);


});