<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
    $request->fulfill();
    return response()->json(['message'=>'verifaied successfully']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('email/resend', [AuthController::class, 'resrndVerification'])->middleware(['throttle:verification']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::get('reset-password/{token}', function (Request $request ,string $token){
    return response()->json([
        'message'=>'Password reset page',
        'token'=>$token,
        'email'=>$request->query('email'),
    ]);
    
})->name('password.reset');


Route::get('product', [ProductController::class, 'index']);
Route::get('product/{droduct_id}', [ProductController::class, 'show']);
Route::get('seller/{seller_id}', [SellerController::class, 'getSellerInfo'])->whereNumber('seller_id');

Route::middleware(['auth:sanctum', 'verified'])->group(function(){

    Route::get('user', [UserController::class, 'getUser']);
    Route::get('user/orders', [UserController::class, 'getUserOrders']);


    Route::get('seller', [SellerController::class, 'index']);
    Route::post('seller', [SellerController::class, 'store']);



    Route::get('product/{product_id}/categories',[ProductController::class, 'getProductCategories']);
    Route::get('category/{category_id}/products',[CategoryController::class, 'getCategoryProducts']);


    Route::post('order', [OrderController::class, 'store']);
    Route::put('order/{order_id}', [OrderController::class, 'update']);

});



Route::middleware(['auth:sanctum', 'seller', 'verified'])->group(function(){

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
    Route::get('admin/product/{product_id}', [AdminController::class, 'getProduct']);

    Route::put('admin/{order_id}/order', [AdminController::class, 'updateOrderStatus']);

    // Route::delete('admin/{seller_id}/seller', [AdminController::class, 'deleteSeller']);
    Route::delete('admin/product/{product_id}', [AdminController::class, 'deleteProduct']);


});