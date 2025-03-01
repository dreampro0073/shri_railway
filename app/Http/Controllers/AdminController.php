<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\User, App\Models\Plan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Room;
use App\Models\Recliner;
use App\Models\Sitting;
use App\Models\CloakRoom;

class AdminController extends Controller {



	public function dashboard(Request $request){

		$sitting_count = 0;
		$total_sitting_count = 0;

		$sitting_count = DB::table("sitting_entries")->where('checkout_status',0)->sum('no_of_adults');
		$sitting_count += DB::table("sitting_entries")->where('checkout_status',0)->sum('no_of_children');
		$sitting_count += DB::table("sitting_entries")->where('no_of_adults',0)->where('checkout_status',0)->sum('no_of_baby_staff');

		$total_sitting = DB::table('client_services')->where('client_id',Auth::user()->client_id)->where('services_id',1)->first();

		if($total_sitting){
			$total_sitting_count = isset($total_sitting->capacity) ? $total_sitting->capacity : 0;
		}

		$avail_sit	=$total_sitting_count-$sitting_count;

		$avail_pods = Room::getAvailPodsAr();
		$avail_cabins = Room::getAvailSinCabinsAr();
		$avail_beds = Room::getAvailBedsAr();

		$booked_pods = Room::getBookedPodsAr();
		$booked_cabins = Room::getBookedSinCabinsAr();
		$booked_beds = Room::getBookedBedsAr();

		$booked_recliner = Recliner::getBookedReclinersAr();
		$avail_recliner = Recliner::getAvailReclinersAr();

		$booked_bags = CloakRoom::getBookedBags();
		
		return view('admin.dashboard', [
            "sidebar" => "dashboard",
            "subsidebar" => "dashboard",
            "sitting_count" => $sitting_count,
            "avail_sit" => $avail_sit,
            "total_sitting_count" => $total_sitting_count,
            "avail_pods" => $avail_pods,
            "avail_cabins" => $avail_cabins,
            "avail_beds" => $avail_beds,
            "booked_pods" => $booked_pods,
            "booked_cabins" => $booked_cabins,
            "booked_beds" => $booked_beds,
            "booked_recliner" => $booked_recliner,
            "avail_recliner" => $avail_recliner,
            "booked_bags" => $booked_bags,
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
        
        $items = DB::table('canteen_items')->where('is_manual',1)->whereNull('barcodevalue')->where('barvalue_avail',0)->where('client_id',Auth::user()->client_id)->get();

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
        
        $items = DB::table('canteen_items')->where('is_manual',1)->where('client_id',Auth::user()->client_id)->get();
        // return view('admin.canteens.canteen_items.mbarcode',compact('items'));

    	// return $pdf->download('invoice.pdf');

        // foreach ($items as $key => $item) {
        // 	$generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        // 	$barcode = $generator->getBarcode($item->barcodevalue, $generator::TYPE_CODE_128);
  
        
        // }

        $pdf = Pdf::loadView('admin.canteens.canteen_items.mbarcode',compact('items'));

    	return $pdf->download('items.pdf');
	}

	public function uploadFile(Request $request){
        $destination = 'uploads/';
        
        if($request->media){
            $file = $request->media;
            $extension = $request->media->getClientOriginalExtension();
            if(in_array($extension, User::fileExtensions())){
                $name = strtotime("now").'.'.strtolower($extension);
                $file = $file->move($destination, $name);
                $data["media"] = $destination.$name;

                $data["success"] = true;
                $data["media_link"] = url($destination.$name);
            }else{
                $data['success'] = false;
                $data['message'] = 'Invalid file format';
            }
        }else{
            $data['success'] = false;
            $data['message'] ='file not found';
        }

        return Response::json($data, 200, array());
    }
}