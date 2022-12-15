<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\DeliveryController;
use App\Http\Controllers\API\HistoryController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\NotificationsController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\VoucherController;
use App\Http\Controllers\API\WalletController;
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


    #wallet
        Route::post('wallet/fund', [WalletController::class, 'fundwallet']);

        Route::get('wallet/view', [WalletController::class, 'viewwallet']);



    #History
        Route::get('history', [HistoryController::class, 'history']);

        Route::get('history/{id}/view', [HistoryController::class, 'viewhistory']);

    
    #Account
        Route::get('profile', [AccountController::class, 'profile']);    

        Route::post('profile/update', [AccountController::class, 'updateprofile']);

        Route::post('profile/password/update', [AccountController::class, 'updatepassword']);

        Route::post('profile/bvn/update', [AccountController::class, 'updatebvn']);

        Route::post('profile/idcards/update', [AccountController::class, 'updateid']);
    
        Route::post('profile/account/close', [AccountController::class, 'closeaccount']);


    #Voucher

        Route::post('voucher/create', [VoucherController::class, 'store']);
        
        Route::get('voucher/view', [VoucherController::class, 'index']);

        Route::post('voucher/{id}/update', [VoucherController::class, 'update']);

        Route::delete('voucher/{id}/delete', [VoucherController::class, 'destroy']);


});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
