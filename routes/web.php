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

use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;



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
Route::get('/print', [SittingController::class,'print']);
Route::get('/print1', [SittingController::class,'print1']);


Route::get('/logout',function(){
	Auth::logout();
	return Redirect::to('/');
});
Route::get('/getHideAmount',function(){
	$user_id = 19;
	$zero_entries = DB::table("e_entries")->where("date", '>', "2024-09-01")->where("added_by", $user_id)->where("paid_amount", 0)->pluck("entry_id")->toArray();

    $sitting_list = DB::table("sitting_entries")->whereIn("id", $zero_entries)->get();
    $all_amount = 0;
    $collect_amount = 0;
    foreach ($sitting_list as $entry) {
        $entry->wh_total_hours = (strtotime($entry->checkout_time) - strtotime($entry->checkin_date)) / 3600;

        dd(strtotime($entry->wh_total_hours));
        $entry->wh_ad_hours = $entry->wh_total_hours * $entry->no_of_adults;
        $entry->wh_ad_amount = $entry->wh_total_hours * 30;
        $entry->wh_ch_hours = $entry->wh_total_hours * $entry->no_of_children;
        $entry->wh_ch_amount = $entry->wh_total_hours * 20;
        $collect_amount = $collect_amount+$entry->paid_amount; 
        $all_amount = $entry->wh_ch_amount+$entry->wh_ad_amount+$all_amount; 
    }

    dd($all_amount, $collect_amount);
    return;
});

Route::get('/getHideAmount', function () {
    $user_id = 19;
    $zero_entries = DB::table("e_entries")
                      ->where("date", '>', "2024-09-01")
                      ->where("added_by", $user_id)
                      ->where("paid_amount", 0)
                      ->pluck("entry_id")
                      ->toArray();

    $sitting_list = DB::table("sitting_entries")->whereIn("id", $zero_entries)->get();
    $all_amount = 0;
    $collect_amount = 0;

    foreach ($sitting_list as $entry) {
        if ($entry->checkout_time && $entry->checkin_date) {
            $time_difference_in_seconds = strtotime($entry->checkout_time) - strtotime($entry->checkin_date);

            $hours = floor($time_difference_in_seconds / 3600);  // Get total hours
            $minutes = floor(($time_difference_in_seconds % 3600) / 60);  // Get remaining minutes

            if ($minutes > 10) {
                $hours += 1;
            }
            $entry->wh_total_hours = $hours;
	        $entry->wh_ad_hours = $entry->wh_total_hours * $entry->no_of_adults;
	        $entry->wh_ad_amount = $entry->wh_total_hours * 30;
	        $entry->wh_ch_hours = $entry->wh_total_hours * $entry->no_of_children;
	        $entry->wh_ch_amount = $entry->wh_total_hours * 20;

	        $collect_amount += $entry->paid_amount;
	        $all_amount += $entry->wh_ch_amount + $entry->wh_ad_amount;
	    }
    }

    dd($all_amount, $collect_amount);

    return;
});


Route::group(['middleware'=>'auth'],function(){
	Route::group(['prefix'=>"admin"], function(){
		Route::get('/set-barcode',[AdminController::class,'setBarcode']);
		Route::get('/print-barcode',[AdminController::class,'printItemsBarcode']);

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

			Route::get('/export', [CloakRoomController::class,'export']);
			
			Route::get('/checkout',[AdminController::class,'checkout']);

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
				Route::get('/print-barcode/{id}',[AdminController::class,'printBarcode']);
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

		Route::post('/uploadFile',[AdminController::class,'uploadFile']);
		

		Route::group(["prefix"=>"expenses"],function(){
			Route::get('/',[ExpenseController::class,'index']);
			Route::get('/add',[ExpenseController::class,'editForm']);
			Route::get('/edit/{expense_id}',[ExpenseController::class,'editForm']);
			Route::get('/print/{expense_id}',[ExpenseController::class,'printExpense']);
			
		});		

		Route::group(["prefix"=>"income"],function(){
			Route::get('/',[IncomeController::class,'index']);
			Route::get('/add',[IncomeController::class,'editForm']);
			Route::get('/edit/{income_id}',[IncomeController::class,'editForm']);
			Route::get('/print/{income_id}',[IncomeController::class,'printIncome']);
		});		

		Route::group(["prefix"=>"summary"],function(){
			Route::get('/',[IncomeController::class,'summary']);
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
		Route::post('/checkout-init',[CloakRoomController::class,'checkoutInit']);
		Route::post('/checkout-store',[CloakRoomController::class,'checkoutStore']);
		Route::post('/checkout-init1',[CloakRoomController::class,'checkoutInit1']);
		Route::post('/checkout-store1',[CloakRoomController::class,'checkoutStore1']);
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

	Route::group(["prefix"=>"expenses"],function(){
		Route::post('/init',[ExpenseController::class,'init']);
		Route::post('/edit',[ExpenseController::class,'edit']);
		Route::post('/store',[ExpenseController::class,'store']);
		Route::get('/delete/{expense_id}',[ExpenseController::class,'delete']);
	});	

	Route::group(["prefix"=>"income"],function(){
		Route::post('/init',[IncomeController::class,'init']);
		Route::post('/edit',[IncomeController::class,'edit']);
		Route::post('/store',[IncomeController::class,'store']);
		Route::get('/delete/{income_id}',[IncomeController::class,'delete']);
	});	

	Route::group(["prefix"=>"summary"],function(){
		Route::post('/init',[IncomeController::class,'summaryInit']);
	});


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
