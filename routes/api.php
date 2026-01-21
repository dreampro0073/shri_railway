<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoomController;
use App\Http\Controllers\AppApiController;
use App\Http\Controllers\AppDailyEntryContoller;

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

Route::group(['prefix'=>"app/v2"], function(){

    Route::get('api_version',[AppApiController::class,'getApiVersion']);
    Route::post('login',[AppApiController::class,'login']);
    Route::post('mobile-login',[AppApiController::class,'mLogin']);

    Route::group(['prefix'=>"daily-entries"], function(){
        Route::post('/init',[AppDailyEntryContoller::class,'initEntries']);
        Route::post('/edit-init',[AppDailyEntryContoller::class,'editEntry']);
        Route::post('/store',[AppDailyEntryContoller::class,'store']);
    });
    Route::group(['prefix'=>"shift"], function(){
        Route::post('/init',[ShiftController::class,'init']);
        Route::post('/prev-init',[ShiftController::class,'prevInit']);

    });
    Route::group(['prefix'=>"users"], function(){
        Route::post('/init',[AppApiController::class,'initUsers']);
        Route::post('/edit-init',[AppApiController::class,'editUser']);
        Route::post('/store',[AppApiController::class,'storeUser']);
    });

    Route::group(['prefix'=>"canteen-items"], function(){
        Route::post('/init',[AppApiController::class,'initCanteenItems']);
        Route::post('/edit',[AppApiController::class,'editCanteenItem']);
        Route::post('/store',[AppApiController::class,'storeCanteenItem']);
        Route::post('/drop-list',[AppApiController::class,'initCanteenItemsDrop']);

        Route::group(['prefix'=>"stocks"], function(){
            Route::post('/init',[AppApiController::class,'initCanteenItemStocks']);
            Route::post('/edit',[AppApiController::class,'editCanteenItemStocks']);
            Route::post('/store',[AppApiController::class,'storeCanteenItemStock']);
        });
    });

    // Route::group(['prefix'=>"items"], function(){
    //     Route::post('/init',[AppApiController::class,'initItems']);
    //     Route::post('/edit-init',[AppApiController::class,'editItem']);
    //     Route::post('/store',[AppApiController::class,'storeItem']);
    // });

    // Route::group(['prefix'=>"canteen-item-stocks"], function(){
    //     Route::post('/init',[AppApiController::class,'initCanteenItemStocks']);
    //     Route::post('/edit',[AppApiController::class,'editCanteenItemStocks']);
    //     Route::post('/store',[AppApiController::class,'storeCanteenItemStock']);
    // });

    Route::post('canteen-item-list/{canteen_id}',[AppApiController::class,'canteenItemList']);
});
