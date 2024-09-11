<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\User, App\Models\Plan;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller {



	public function dashboard(Request $request){
		return view('admin.dashboard', [
            "sidebar" => "dashboard",
            "subsidebar" => "dashboard",
        ]);
	}

	public function checkout(Request $request){
		    
		return view('admin.cloakrooms.checkout_page', [
            "sidebar" => "checkout-cloak",
            "subsidebar" => "checkout-cloak",
        ]);
	}	

	public function sitting(Request $request){
		    
		return view('admin.entries.index_new', [
            "sidebar" => "sitting",
            "subsidebar" => "sitting",
        ]);
	}

	public function canteenItems(Request $request){
		    
		return view('admin.canteens.canteen_items.index', [
            "sidebar" => "cant_items",
            "subsidebar" => "cant_items",
        ]);
	}
	public function canteenItemStocks(Request $request,$canteen_item_id=0){
		    
		return view('admin.canteens.canteen_items.stock', [
            "sidebar" => "cant_items",
            "subsidebar" => "cant_items",
            "canteen_item_id" => $canteen_item_id,
        ]);
	}

	public function dailyEntries(Request $request){
		return view('admin.canteens.daily_entries.index', [
            "sidebar" => "daily_entries",
            "subsidebar" => "daily_entries",
        ]);
	}

	public function barcodeGen($type){
		//1- Tea -10
		//2- Tea -20
		//3- Coffee -10
		//4- Coffee -20
		//1- Tea -10
		//1- Tea -10
	}

	public function printBarcode($id=0){
		$item = DB::table('canteen_items')->where('id',$id)->first();
		if($item){
			return view('admin.canteens.canteen_items.barcode',compact('item'));

		}else{
			return "NO data found";
		}
	}
	public function setBarcode(){
        
        $items = DB::table('canteen_items')->where('is_manual',1)->whereNull('barcodevalue')->where('barvalue_avail',0)->get();

        $barcode = strtotime("now")."12";
        $count = 0;

        foreach ($items as $key => $item) {
        	$check = DB::table("canteen_items")->where('barcodevalue',$barcode)->first();
        	if($check){
        		$barcode = $barcode+30;
        	}
        	DB::table('canteen_items')->where('id',$item->id)->update([
        		'barcodevalue' => $barcode+$key,
        		'barvalue_avail' => 1,
        	]);
        	$count++;
        }

        return "Done".$count;
	}
	public function printItemsBarcode(){
        
        $items = DB::table('canteen_items')->where('is_manual',1)->get();
        // return view('admin.canteens.canteen_items.mbarcode',compact('items'));

    	// return $pdf->download('invoice.pdf');

        // foreach ($items as $key => $item) {
        // 	$generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        // 	$barcode = $generator->getBarcode($item->barcodevalue, $generator::TYPE_CODE_128);
  
        
        // }

        $pdf = Pdf::loadView('admin.canteens.canteen_items.mbarcode',compact('items'));

    	return $pdf->download('items.pdf');
	}
}