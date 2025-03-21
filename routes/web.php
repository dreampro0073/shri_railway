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
use App\Http\Controllers\GodownsController;
use App\Http\Controllers\ReclinerController;
use App\Http\Controllers\AppApiController;
use App\Http\Controllers\AppDailyEntryContoller;
use App\Http\Controllers\AadharDetailsController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ClientSettingController;
use App\Http\Controllers\ScanningController;



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

Route::get('/error',function(){
	return view('error');
});

Route::get('/barcode-gen', [AdminController::class,'barcodeGen']);
Route::get('/print', [SittingController::class,'print']);
Route::get('/print1', [SittingController::class,'print1']);

Route::get('/aadhar/upload-by-mobile/{id}', [AadharDetailsController::class, 'uploadByMobileFile']);
Route::post('/aadhar/upload-by-mobile/{id}', [AadharDetailsController::class, 'postUploadByMobileFile']);


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
		Route::post('/uploadFile',[AdminController::class,'uploadFile']);
		Route::get('/backup-data', [BackupController::class,'dumpData']);
		// Route::get('/recliners-set', [ReclinerController::class,'reclinersSet']);
		Route::get('/set-barcode',[AdminController::class,'setBarcode']);
		Route::get('/print-barcode',[AdminController::class,'printItemsBarcode']);

		Route::get('/dashboard',[AdminController::class,'dashboard']);
		
		Route::get('/reset-password',[UserController::class,'resetPassword']);
		Route::post('/reset-password',[UserController::class,'updatePassword']);

		Route::middleware(['check.sitting'])->group(function () {
		   Route::group(['prefix'=>"sitting"], function(){
				Route::get('/',[SittingController::class,'sitting']);
				Route::get('/update-print/{slip_id}',[SittingController::class,'updatePrint']);
				Route::get('/print-unq/{type}/{print_id?}', [SittingController::class,'printPostUnq']);
				Route::get('/print/{id?}', [SittingController::class,'printPost']);
				// Route::get('/print-report', [SittingController::class,'printReports']);
				Route::get('/checkout-without-penalty/{id?}', [SittingController::class,'checkoutWithoutPenalty']);
				Route::get('/change-pay-type/{id?}', [SittingController::class,'changePayType']);

			});
		});

		Route::middleware(['check.cloak'])->group(function () {
		   	Route::group(['prefix'=>"cloak-rooms"], function(){
				Route::get('/',[CloakRoomController::class,'index']);
				Route::get('/all',[CloakRoomController::class,'allRooms']);
				Route::get('/print-unq/{type}/{print_id?}', [CloakRoomController::class,'printPostUnq']);
				Route::get('/print/{id?}', [CloakRoomController::class,'printPost']);
				Route::get('/export', [CloakRoomController::class,'export']);
			});	
		});

		Route::middleware(['check.canteen'])->group(function () {
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
		});
		Route::middleware(['check.massage'])->group(function () {
		   	Route::group(['prefix'=>"massage"], function(){
				Route::get('/',[MassageController::class,'massage']);
				Route::get('/print/{id?}', [MassageController::class,'printPost']);
				
			});
		});
		Route::middleware(['check.locker'])->group(function () {
		   	Route::group(['prefix'=>"locker"], function(){
				Route::get('/',[LockerController::class,'index']);
				Route::get('/print/{id?}', [LockerController::class,'printPost']);
				
			});	
		});
		Route::middleware(['check.ledger'])->group(function () {
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
		

	
		Route::middleware(['check.recliner'])->group(function () {
		   	Route::group(['prefix'=>"recliners"], function(){
				Route::get('/',[ReclinerController::class,'recliners']);
				Route::get('/update-print/{slip_id}',[ReclinerController::class,'updatePrint']);
				Route::get('/print-unq/{type}/{print_id?}', [ReclinerController::class,'printPostUnq']);
				Route::get('/print/{id?}', [ReclinerController::class,'printPost']);
				Route::get('/checkout-without-penalty/{id?}', [ReclinerController::class,'checkoutWithoutPenalty']);
				Route::get('/change-pay-type/{id?}', [ReclinerController::class,'changePayType']);

			});
		});

		Route::middleware(['check.room'])->group(function () {
		   	Route::get('/all-rooms',[RoomController::class,'allEntries']);
		
			Route::group(['prefix'=>"rooms"], function(){
				Route::get('/{type}',[RoomController::class,'index']);
				Route::get('/print/{id?}', [RoomController::class,'printPost']);

			});
		});
		Route::middleware(['check.scanning'])->group(function () {
		   	Route::group(['prefix'=>"scanning"], function(){
				Route::get('/',[ScanningController::class,'index']);
				Route::get('/print/{print_id}',[ScanningController::class,'printBill']);
				Route::get('/print-qr/{print_id}',[ScanningController::class,'printQR']);
			});
		});

		Route::group(['prefix'=>"shift"], function(){
			Route::get('/current',[ShiftController::class,'index']);
			Route::get('/print/{type}',[ShiftController::class,'print']);
		});

		// Route::get('collect-cloak', [CloakRoomCollectController::class,'collectCloak']);
		// Route::get('/collect-sitting',[SittingCollectController::class,'collectSitting']);

		// Route::group(['prefix'=>"users"], function(){
		// 	Route::get('/',[UserController::class,'users']);
		// });

		// Route::group(["prefix"=>"godowns"],function(){
		// 	Route::get('/',[GodownsController::class,'index']);
		// 	Route::get('/history/{g_stock_id}',[GodownsController::class,'history']);
		// 	Route::get('/set-gid',[GodownsController::class,'setGid']);
		// });

		Route::group(['prefix'=>"clients"], function(){
			Route::get('/set-amount',[ClientSettingController::class,'setAmount']);
			Route::get('/shift-status',[ClientSettingController::class,'shiftStatus']);
		});		
	});
});

Route::get('set-slip-id',[SittingController::class,'setSlipId']);
Route::get('view-scanning/{print_id}',[ScanningController::class,'viewScanning']);

Route::group(['prefix'=>"api"], function(){	
	Route::post('/set-checkout-alert',[UserController::class,'setCheckoutAlert']);
	Route::group(['prefix'=>"shift"], function(){
		Route::post('/init',[ShiftController::class,'init']);
		Route::post('/prev-init',[ShiftController::class,'prevInit']);
	});

	Route::group(["prefix"=>"aadhar"],function(){
	    Route::post('/fetch',[AadharDetailsController::class,'fetchData']);
	    Route::post('/update_details',[AadharDetailsController::class,'updateDetails']);
	    Route::post('/file-upload', [AadharDetailsController::class, 'uploadFile']);
	});

	Route::group(['prefix'=>"sitting"], function(){
		Route::post('/init',[SittingController::class,'initEntries']);
		Route::post('/edit-init',[SittingController::class,'editEntry']);
		Route::post('/store',[SittingController::class,'store']);
		Route::post('/cal-check',[SittingController::class,'calCheck']);
		Route::post('/checkout-init/{type}',[SittingController::class,'checkoutInit']);	
		Route::post('/checkout-store',[SittingController::class,'checkoutStore']);
		Route::post('/checkout-new/{type}',[SittingController::class,'newCheckout']);
		Route::post('/checkout-alert',[SittingController::class,'checkoutAlert']);
			
	});

	Route::group(['prefix'=>"recliners"], function(){
		Route::post('/init',[ReclinerController::class,'initEntries']);
		Route::post('/edit-init',[ReclinerController::class,'editEntry']);
		Route::post('/store',[ReclinerController::class,'store']);
		Route::post('/cal-check',[ReclinerController::class,'calCheck']);
		Route::post('/checkout-init/{type}',[ReclinerController::class,'checkoutInit']);	
		Route::post('/checkout-store',[ReclinerController::class,'checkoutStore']);
		Route::post('/checkout-new/{type}',[ReclinerController::class,'newCheckout']);
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
		Route::post('/active-user',[UserController::class,'activeUser']);

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

	Route::group(['prefix'=>"godowns"], function(){
		Route::post('/init',[GodownsController::class,'init']);
		Route::post('/edit',[GodownsController::class,'edit']);
		Route::post('/store',[GodownsController::class,'store']);
		Route::post('/init-history/{g_stock_id}',[GodownsController::class,'initHistory']);
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


	Route::group(['prefix'=>"rooms"], function(){
		Route::post('/init/{type}',[RoomController::class,'initEntry']);
		Route::post('/init-all',[RoomController::class,'initAllEntry']);
		Route::post('/edit-init',[RoomController::class,'editEntry']);
		Route::post('/store/{type}',[RoomController::class,'store']);
		Route::post('/cal-check',[RoomController::class,'calCheck']);
		Route::post('/checkout-init',[RoomController::class,'checkoutInit']);
		Route::post('/checkout-store',[RoomController::class,'checkoutStore']);
		Route::get('/delete/{id}',[RoomController::class,'delete']);
		Route::post('/init-single-entry',[RoomController::class,'initSingleEntry']);
		Route::get('/delete-e-entry/{entry_id}/{e_entry_id}',[RoomController::class,'deleteEnEntry']);

	});

	Route::group(['prefix'=>"clients"], function(){
		Route::post('/init-amount-setting',[ClientSettingController::class,'initAmountSetting']);
		Route::post('/store-amount-setting',[ClientSettingController::class,'storeAmountSetting']);
		Route::post('/shift-status',[ClientSettingController::class,'initShiftStatus']);
	});


	Route::group(['prefix'=>"scanning"], function(){
		Route::post('/init',[ScanningController::class,'init']);
		Route::post('/store',[ScanningController::class,'store']);
	});
});

Route::group(['prefix'=>"app-api"], function(){
	
	// Route::post('/get',[AppApiController::class,'login']);
    // Route::post('/m-login',[AppApiController::class,'mLogin']);
    // Route::post('/change_password',[AppApiController::class,'changePassword']);
    // Route::post('/reasons',[AppApiController::class,'reasons']);
	// Route::post('/delete',[AppApiController::class,'deleteMyAccount']);

	Route::group(["prefix"=>"app-login"],function(){
	    Route::post('/login',[AppApiController::class,'login']);
	    Route::post('/m-login',[AppApiController::class,'mLogin']);
	    Route::post('/change_password',[AppApiController::class,'changePassword']);
	});	

	Route::group(["prefix" => 'delete-account'],function(){
	    Route::post('/reasons',[AppApiController::class,'reasons']);
	    Route::post('/delete',[AppApiController::class,'deleteMyAccount']);
	});

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

	Route::group(['prefix'=>"canteens"], function(){
		Route::post('/init',[AppApiController::class,'initCanteens']);
		Route::post('/edit-init',[AppApiController::class,'editCanteen']);
		Route::post('/store',[AppApiController::class,'storeCanteen']);
	});

	// Route::group(['prefix'=>"items"], function(){
	// 	Route::post('/init',[AppApiController::class,'initItems']);
	// 	Route::post('/edit-init',[AppApiController::class,'editItem']);
	// 	Route::post('/store',[AppApiController::class,'storeItem']);
	// });
	Route::group(['prefix'=>"canteen-items"], function(){
		Route::post('/init',[AppApiController::class,'initCanteenItems']);
		Route::post('/edit',[AppApiController::class,'editCanteenItem']);
		Route::post('/store',[AppApiController::class,'storeCanteenItem']);
		Route::post('/drop-list',[AppApiController::class,'initCanteenItemsDrop']);

	});

	Route::group(['prefix'=>"canteen-item-stocks"], function(){
		Route::post('/init',[AppApiController::class,'initCanteenItemStocks']);
		Route::post('/edit',[AppApiController::class,'editCanteenItemStocks']);
		Route::post('/store',[AppApiController::class,'storeCanteenItemStock']);
	});

	Route::post('canteen-item-list/{canteen_id}',[AppApiController::class,'canteenItemList']);
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
