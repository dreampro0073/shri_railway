<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CloakRoomController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SittingController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\MassageController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\CloakRoomCollectController;
use App\Http\Controllers\SittingCollectController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [UserController::class,'login'])->name("login");
Route::post('/login', [UserController::class,'postLogin']);
Route::get('/backup-data', [SittingController::class,'dumpSittingData']);
Route::get('/barcode-gen', [AdminController::class,'barcodeGen']);


Route::get('/logout',function(){
	Auth::logout();
	return Redirect::to('/');
});

Route::group(['middleware'=>'auth'],function(){
	Route::group(['prefix'=>"admin"], function(){
		Route::get('/dashboard',[AdminController::class,'dashboard']);
		Route::get('/reset-password',[UserController::class,'resetPassword']);
		Route::post('/reset-password',[UserController::class,'updatePassword']);

		Route::group(['prefix'=>"sitting"], function(){
			Route::get('/',[SittingController::class,'sitting']);
			Route::get('/update-print/{slip_id}',[SittingController::class,'updatePrint']);
			Route::get('/print-unq/{type}/{print_id?}', [SittingController::class,'printPostUnq']);
			Route::get('/print/{id?}', [SittingController::class,'printPost']);
			// Route::get('/print-report', [SittingController::class,'printReports']);
			Route::get('/checkout-without-penalty/{id?}', [SittingController::class,'checkoutWithoutPenalty']);
			Route::get('/change-pay-type/{id?}', [SittingController::class,'changePayType']);

		});
		

		Route::group(['prefix'=>"shift"], function(){
			Route::get('/current',[ShiftController::class,'index']);
			Route::get('/print/{type}',[ShiftController::class,'print']);
		});
		Route::group(['prefix'=>"cloak-rooms"], function(){
			Route::get('/',[CloakRoomController::class,'index']);
			Route::get('/all',[CloakRoomController::class,'allRooms']);
			Route::get('/print-unq/{type}/{print_id?}', [CloakRoomController::class,'printPostUnq']);
			Route::get('/print/{id?}', [CloakRoomController::class,'printPost']);
			Route::get('/export', [CloakRoomController::class,'export']);
		});	
	
		Route::get('collect-cloak', [CloakRoomCollectController::class,'collectCloak']);
		Route::get('/collect-sitting',[SittingCollectController::class,'collectSitting']);

		Route::group(['prefix'=>"users"], function(){
			Route::get('/',[UserController::class,'users']);
		});

		Route::group(['prefix'=>"canteens"], function(){
			Route::group(['prefix'=>"items"], function(){
				Route::get('/',[AdminController::class,'canteenItems']);
				Route::get('/stock/{canteen_item_id}',[AdminController::class,'canteenItemStocks']);
			});


		});

		Route::group(['prefix'=>"daily-entries"], function(){
			Route::get('/',[AdminController::class,'dailyEntries']);
			Route::get('/print/{id}',[ApiController::class,'printBill']);
		});

		Route::group(['prefix'=>"massage"], function(){
			Route::get('/',[MassageController::class,'massage']);
			Route::get('/print/{id?}', [MassageController::class,'printPost']);
			
		});
		Route::group(['prefix'=>"locker"], function(){
			Route::get('/',[LockerController::class,'index']);
			Route::get('/print/{id?}', [LockerController::class,'printPost']);
			
		});	
	});
});

Route::get('set-slip-id',[SittingController::class,'setSlipId']);

Route::group(['prefix'=>"api"], function(){	
	Route::post('/set-checkout-alert',[UserController::class,'setCheckoutAlert']);

	Route::group(['prefix'=>"shift"], function(){
		Route::post('/init',[ShiftController::class,'init']);
		Route::post('/prev-init',[ShiftController::class,'prevInit']);
	});
	Route::group(['prefix'=>"sitting"], function(){
		Route::post('/init',[SittingController::class,'initEntries']);
		Route::post('/edit-init',[SittingController::class,'editEntry']);
		Route::post('/store',[SittingController::class,'store']);
		Route::post('/cal-check',[SittingController::class,'calCheck']);
		Route::post('/checkout-init/{type}',[SittingController::class,'checkoutInit']);	
		Route::post('/checkout-store',[SittingController::class,'checkoutStore']);
		// Route::get('/delete/{id}',[SittingController::class,'delete']);
		Route::post('/checkout-new/{type}',[SittingController::class,'newCheckout']);
		Route::post('/checkout-alert',[SittingController::class,'checkoutAlert']);
			
	});
	Route::group(['prefix'=>"cloak-rooms"], function(){
		Route::post('/init/{type}',[CloakRoomController::class,'initRoom']);
		Route::post('/edit-init',[CloakRoomController::class,'editRoom']);
		Route::post('/store',[CloakRoomController::class,'store']);
		Route::post('/cal-check',[CloakRoomController::class,'calCheck']);
		Route::post('/checkout-init/{type}',[CloakRoomController::class,'checkoutInit']);
		Route::post('/checkout-store',[CloakRoomController::class,'checkoutStore']);
		Route::get('/delete/{id}',[CloakRoomController::class,'delete']);
	});

	Route::group(['prefix'=>"collect-cloak"], function(){
		Route::post('/init',[CloakRoomCollectController::class,'initRoom']);
		Route::post('/store',[CloakRoomCollectController::class,'storeCollectCloak']);
		Route::post('/store-pen',[CloakRoomCollectController::class,'storePen']);
	});
	Route::group(['prefix'=>"users"], function(){
		Route::post('/init',[UserController::class,'initUsers']);
		Route::post('/edit-init',[UserController::class,'editUser']);
		Route::post('/store',[UserController::class,'storeUser']);
	});

	Route::group(['prefix'=>"daily-entries"], function(){
		Route::post('/init',[ApiController::class,'initEntries']);
		Route::post('/edit-init',[ApiController::class,'editEntry']);
		Route::post('/store',[ApiController::class,'store']);
	});

	Route::group(['prefix'=>"canteen-items"], function(){
		Route::post('/init',[ApiController::class,'initCanteenItems']);
		Route::post('/edit',[ApiController::class,'editCanteenItem']);
		Route::post('/store',[ApiController::class,'storeCanteenItem']);
		Route::post('/drop-list',[ApiController::class,'initCanteenItemsDrop']);

		Route::group(['prefix'=>"stocks"], function(){
			Route::post('/init',[ApiController::class,'initCanteenItemStocks']);
			Route::post('/edit',[ApiController::class,'editCanteenItemStocks']);
			Route::post('/store',[ApiController::class,'storeCanteenItemStock']);
		});
	});


	Route::group(['prefix'=>"massage"], function(){
		Route::post('/init',[MassageController::class,'initMassage']);
		Route::post('/edit-init',[MassageController::class,'editMassage']);
		Route::post('/store',[MassageController::class,'store']);
		Route::get('/delete/{id}',[MassageController::class,'delete']);

	});
	Route::group(['prefix'=>"locker"], function(){
		Route::post('/init',[LockerController::class,'initLocker']);
		Route::post('/edit-init',[LockerController::class,'editLocker']);
		Route::post('/store',[LockerController::class,'store']);
		Route::post('/cal-check',[LockerController::class,'calCheck']);
		Route::post('/checkout-init',[LockerController::class,'checkoutInit']);
		Route::post('/checkout-store',[LockerController::class,'checkoutStore']);
		Route::get('/delete/{id}',[LockerController::class,'delete']);
	});
	
	Route::post('canteen-item-list/{canteen_id}',[ApiController::class,'canteenItemList']);

	

	Route::group(['prefix'=>"collect-sitting"], function(){
		Route::post('/init',[SittingCollectController::class,'init']);
		Route::post('/store',[SittingCollectController::class,'storeCollectSit']);
		Route::post('/store-pen',[SittingCollectController::class,'storePen']);
	});
});

// Route::group(['prefix'=>"api"], function(){
// 	Route::group(['prefix'=>"canteens"], function(){
// 		Route::post('/init',[ApiController::class,'initCanteens']);
// 		Route::post('/edit-init',[ApiController::class,'editCanteen']);
// 		Route::post('/store',[ApiController::class,'storeCanteen']);
// 	});

// 	Route::group(['prefix'=>"users"], function(){
// 		Route::post('/init',[ApiController::class,'initUsers']);
// 		Route::post('/edit-init',[ApiController::class,'editUser']);
// 		Route::post('/store',[ApiController::class,'storeUser']);
// 	});
// 	Route::group(['prefix'=>"items"], function(){
// 		Route::post('/init',[ApiController::class,'initItems']);
// 		Route::post('/edit-init',[ApiController::class,'editItem']);
// 		Route::post('/store',[ApiController::class,'storeItem']);
// 	});
	
// });
