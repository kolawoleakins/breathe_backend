<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\DeliveryController;
use App\Http\Controllers\API\NotificationsController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::post('resetpassword', [AuthController::class, 'passwordreset']);

Route::post('updatepassword', [AuthController::class, 'updatepassword']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function(){
    
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('products', [ProductController::class, 'index']);

    Route::get('products/{id}/show', [ProductController::class, 'show']);

    Route::post('product/add', [ProductController::class, 'store']);

    Route::post('products/{id}/update', [ProductController::class, 'update']);

    Route::delete('products/{id}/delete', [ProductController::class, 'destroy']);

    Route::post('checkout', [CheckoutController::class, 'index']);

    Route::post('verifypayment', [CheckoutController::class, 'checkpayment']);

    #orders
    Route::get('orders', [CheckoutController::class, 'orders']);

    Route::post('orderdetail', [CheckoutController::class, 'orderdetail']);


    #notifications
    Route::get('notifications', [NotificationsController::class, 'notifications']);

    #notifications
    Route::get('notification/{id}/view', [NotificationsController::class, 'viewnotification']);

    
    #arrival
    Route::post('arrival', [DeliveryController::class, 'arrival']);

});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
