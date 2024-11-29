<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\DailyEntry, App\Models\User;

class AppDailyEntryContoller extends Controller {

	// public function inst(){
	// 	$canteens = DB::table('canteens')->get();

	// 	foreach ($canteens as $key => $canteen) {
	// 		$items = DB::table('items')->get();

	// 		foreach ($items as $key => $item) {
	// 			DB::table('canteen_items')->insert([
	// 				'canteen_id' => $canteen->id,
	// 				'item_id' => $item->id,
	// 				'stock' => 0
	// 			]);
	// 		}
	// 	}
	// }

	public function dailyEntries(){
		$sidebar = "daily-entries";
		return view('admin.daily_entries.index',compact('sidebar'));
	}

	public function initEntries(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));

		$page_no = $request->page_no;
		$date = $request->date;
		$max_per_page = 10;

		$entries = DailyEntry::select('daily_entries.*')->where('canteen_id',$user->canteen_id);


		if($request->search_field){

            $search = $request->search_field;

            $entries = $entries->where(function($query) use ($search) {

                $query->where('daily_entries.name', 'LIKE' ,"%".$search."%")->orWhere('daily_entries.mobile', 'LIKE' ,"%".$search."%");
            });
        }
		
		$entries = $entries->skip(($page_no-1)*$max_per_page)->take($max_per_page)->orderBy('id','DESC')->where('canteen_id',$user->canteen_id)->get();

		foreach ($entries as $key => $entry) {
			$entry->time = date("h:i a,d M",strtotime($entry->created_at));
		}

		$data['success'] = true;
		$data['entries'] = $entries;

		return Response::json($data, 200, []);
	}	
	
	public function editEntry(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));
		$s_entry = DailyEntry::where('id', $request->entry_id)->first();

		if($s_entry){
			$s_entry->mobile_no = $s_entry->mobile_no*1;
			$s_entry->paid_amount = $s_entry->paid_amount*1;
			$s_entry->pay_type = $s_entry->pay_type*1;
			if($s_entry->pay_type == 1){
				$s_entry->show_pay_type = 'UPI';
			}
			if($s_entry->pay_type == 2){
				$s_entry->show_pay_type = 'Cash';
			}
			$s_entry->show_date = date("d M Y");
			$s_entry->total_amount = $s_entry->total_amount*1;

			$s_entry->created_time = date("h:i A",strtotime($s_entry->created_at));


			$products = DB::table('daily_entry_items')->select('daily_entry_items.*','canteen_items.id','canteen_items.price','canteen_items.item_name')->leftJoin('canteen_items','canteen_items.id','=','daily_entry_items.canteen_item_id')->where('daily_entry_items.entry_id','=',$s_entry->id)->get();

			$s_entry->products = $products;

			$data['success'] = true;
			$data['s_entry'] = $s_entry;
		}else{
			$data['success'] = false;
			
		}

		return Response::json($data, 200, []);

	}
	
	public function store(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$cre = [
			'name'=>$request->name,
			// 'mobile' => $request->mobile,
		];
		$rules = [
			'name'=>'required',
			// 'mobile'=>'required',
		];

		$validator = Validator::make($cre,$rules);
		$date = DailyEntry::getPDate();

		if($validator->passes()){
			$unique_id = strtotime("now");
			$ins_data = [
				'unique_id' => strtotime("now"),
				'canteen_id' => $user->canteen_id,
				'added_by' => $user->id,
				'name' => $request->has('name')?$request->name:null,
				'mobile' => $request->has('mobile')?$request->mobile:null,
				'unique_id' => $unique_id,
				'total_amount' =>$request->total_amount,
				'pay_type' =>$request->pay_type,
				'date' => date('Y-m-d'),
				'created_at' => date("Y-m-d H:i:s"),
			];


			$entry_id = DB::table('daily_entries')->insertGetId($ins_data);

			$items = $request->products;

			$total_amount = 0;
			if(sizeof($items) > 0){
				foreach ($items as $key => $item) {

					if($item['quantity'] !=0){
						DB::table('daily_entry_items')->insert([
							'canteen_item_id' => $item['canteen_item_id'],
							'entry_id' => $entry_id,
							'paid_amount' => $item['paid_amount'],
							'quantity' => $item['quantity'],
						]);

						$check = DB::table('canteen_items')->where('id',$item['canteen_item_id'])->first();
						$avil_stock = $check->stock;
						DB::table('canteen_items')->where('id',$item['canteen_item_id'])->update([
							'stock' => $avil_stock - $item['quantity'],
						]);
					}		
				}
			}

			$data['success'] = true;
			$data['unique_id'] = $unique_id;
			$data['billing_date'] = date("M d Y h:i:sA");
			$data['entry_id'] = $entry_id;
			$data['message'] = "Item's detail is stored successfully!";

		} else {
			$message = $validator->errors()->first();
			$data['success'] = false;
			$data['message'] = $message;
		}

		return Response::json($data, 200, []);
	}
}
