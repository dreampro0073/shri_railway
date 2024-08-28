<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\User, App\Models\Plan;

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
}