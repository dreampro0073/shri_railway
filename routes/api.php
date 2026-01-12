<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoomController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=>"rooms"], function(){
    Route::post('/avail-init',[RoomController::class,'availInit']);
    Route::post('/get-amount',[RoomController::class,'getRoomAmount']);
    Route::post('/get-checkout-time',[RoomController::class,'getCheckoutTime']);
    Route::post('/book-room',[RoomController::class,'bookRoom']);

});

Route::group(['prefix'=>"payment"], function(){
    Route::post('create-order',[RoomController::class,'createOrder']);
    Route::post('callback',[RoomController::class,'callback']);
    Route::get('webhook',[RoomController::class,'webhook']);

});