<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Entry, App\Models\User, App\Models\Recliner,App\Models\Massage,App\Models\Locker,App\Models\LockerPen;

class ReclinerController extends Controller {

	public function recliners(Request $request){

		$service_ids = Session::get('service_ids');
		if(in_array(1, $service_ids)){
			return view('admin.recliners.index_new', [
	            "sidebar" => "rec",
	            "subsidebar" => "rec",
	        ]);
		} else {
			die("Not authorized!");
		}
	}
	public function initEntries(Request $request){

		if(Auth::user()->priv == 2){
			Entry::setCheckStatus();
		}

		$entries = Recliner::select('recliners_entries.*')->where("recliners_entries.client_id", Auth::user()->client_id);
		if($request->slip_id){
			$entries = $entries->where('recliners_entries.slip_id', $request->slip_id);
		}		
		if($request->name){
			$entries = $entries->where('recliners_entries.name', 'LIKE', '%'.$request->name.'%');
		}		
		if($request->mobile_no){
			$entries = $entries->where('recliners_entries.mobile_no', 'LIKE', '%'.$request->mobile_no.'%');
		}		
		if($request->pnr_uid){
			$entries = $entries->where('recliners_entries.pnr_uid', 'LIKE', '%'.$request->pnr_uid.'%');
		}		
		
		
		$entries = $entries->orderBy("checkout_status", 'ASC')->orderBy('id', "DESC");
		$entries = $entries->take(50);
		$entries = $entries->get();
		foreach ($entries as $item) {
			$item->show_time = date("h:i A",strtotime($item->check_in)).' - '.date("h:i A",strtotime($item->check_out));

			$e_total = Recliner::eSum($item->id);

			$item->paid_amount = $item->paid_amount + $e_total;
			$item->str_checkout_time = strtotime($item->checkout_date);

			$item->show_e_ids = Recliner::getEnos($item->rec_ids);
		}
		$rate_list = Recliner::rateList();

		$pay_types = Entry::payTypes();
		$hours = Entry::hours();
		$data['success'] = true;
		$data['entries'] = $entries;
		$data['pay_types'] = $pay_types;
		$data['hours'] = $hours;
		$data['rate_list'] = $rate_list;
		$data['avail_recliners'] = Recliner::getAvailRecliners();
		return Response::json($data, 200, []);
	}	
	
	public function editEntry(Request $request){
		$rec_entry = Recliner::where('id', $request->entry_id)->where("client_id", Auth::user()->client_id)->first();


		$sl_recliners = [];

		if($rec_entry){
			$rec_entry->mobile_no = $rec_entry->mobile_no*1;
			$rec_entry->train_no = $rec_entry->train_no*1;
			$rec_entry->pnr_uid = $rec_entry->pnr_uid;
			

			$e_total = Recliner::eSum($rec_entry->id);
			$rec_entry->paid_amount = $rec_entry->paid_amount*1 + $e_total;
			$rec_entry->total_amount = $rec_entry->paid_amount;

			$rec_entry->check_in = date("h:i A",strtotime($rec_entry->check_in));
			$rec_entry->check_out = date("h:i A",strtotime($rec_entry->check_out));

			// $rec_entry->show_valid_up = $this->getValTime($rec_entry->hours_occ,$rec_entry->date,$rec_entry->check_in);
			$rec_entry->show_valid_up = date("h:i A d-m-Y",strtotime($rec_entry->checkout_date));

			$sl_recliners = explode(',', $rec_entry->rec_ids);

			$rec_entry->show_e_ids = Recliner::getEnos($rec_entry->rec_ids);
		}

		$data['success'] = true;
		$data['rec_entry'] = $rec_entry;
		$data['sl_recliners'] = $sl_recliners;
		return Response::json($data, 200, []);
	}
	
	// public function checkoutInit(Request $request,$type=0){
	// 	if($type== 1){
	// 		$sitting_entry = Recliner::where('id', $request->entry_id)->where("checkout_status", 0)->where("client_id", Auth::user()->client_id)->first();
	// 	}else{
	// 		$productName =$request->productName;
    // 		$sitting_entry = Recliner::where('unique_id', $productName)->where("checkout_status", 0)->where("client_id", Auth::user()->client_id)->first();
	// 	}

	// 	if($sitting_entry){
    // 		$now_time = strtotime(date("Y-m-d H:i:s",strtotime("-10 minutes")));
	// 		$current_time = strtotime(date("Y-m-d H:i:s"));
    // 		$checkout_time = strtotime($sitting_entry->checkout_date);

	// 		if($checkout_time > $now_time){
	// 			$sitting_entry->checkout_status = 1;
	// 			$sitting_entry->checkout_time = date("Y-m-d H:i:s"); 
	// 			$sitting_entry->checkout_by = Auth::id();
	// 			$sitting_entry->save();

	// 			DB::table('checks')->insert([
	// 				'entry_id' => $sitting_entry->id,
	// 				'slip_id' => $sitting_entry->slip_id,
	// 				'added_by' => Auth::id(),
	// 				'time' => date("Y-m-d H:i:s"),
	// 				'type' => 1
	// 			]);

	// 			$data['success'] = true;
	// 			$data["message"] = "Successfully Checkout";

	// 		}else{
	// 			$extra_time = $current_time-$checkout_time;
	// 			$extra_time = round($extra_time/60/60, 2);
	// 			$extra_hours = explode(".",$extra_time);
	// 			$ex_hours = $extra_hours[0]*1;
	// 			if($extra_hours[1] > 10){
	// 				$ex_hours += 1;
	// 			}
	// 			$sitting_entry->mobile_no = $sitting_entry->mobile_no*1;
	// 			$sitting_entry->train_no = $sitting_entry->train_no*1;
	// 			$sitting_entry->pnr_uid = $sitting_entry->pnr_uid;
				
	// 			$e_total = Recliner::eSum($sitting_entry->id);

	// 			$sitting_entry->paid_amount = $sitting_entry->paid_amount*1 + $e_total;
	// 			$sitting_entry->total_amount = $sitting_entry->paid_amount;
	// 			$sitting_entry->check_in = date("h:i A",strtotime($sitting_entry->check_in));
	// 			$sitting_entry->check_out = date("h:i A",strtotime($sitting_entry->check_out));
	// 			$sitting_entry->checkout_date = date("d-m-Y h:i A",strtotime($sitting_entry->checkout_date));
	// 			$data['success'] = false;
	// 			$data['ex_hours'] = $ex_hours;

	// 			$data['sitting_entry'] = $sitting_entry;
	// 		}
	// 	} else {
	// 		$data['success'] = true;
	// 		$data["message"] = "Already Checkout";
	// 	}
	// 	return Response::json($data, 200, []);
	// }

	public function calCheck(Request $request){
		$entry = Recliner::find($request->entry_id);
		if($entry){
			$show_checkout_date = $this->getValTime($request->hours_occ,$entry->date,$entry->check_in);
		}else{
			$show_checkout_date = $this->getValTime($request->hours_occ,'','');
		}
		$data['success'] = true;
		$data['show_valid_up'] = date("h:i A d-m-Y",strtotime($show_checkout_date));
		return Response::json($data, 200, []);
	}


	function getValTime($hours_occ=0,$date='',$check_in=''){
		if($check_in !=''){
			$check_in_date = $date." ".$check_in;
			$no_of_min = $hours_occ*60;
			$show_valid_up = date("Y-m-d H:i:s",strtotime("+".$no_of_min." minutes",strtotime($check_in_date)));
		}else{
			$date = Entry::getPDate();
			$check_in_time = date("H:i:s");
			$check_in_date = $date.' '.$check_in_time;
			$no_of_min = $hours_occ*60;
			$show_valid_up = date("Y-m-d H:i:s",strtotime("+".$no_of_min." minutes",strtotime($check_in_date)));
		}
		return  date("h:i A d-m-Y",strtotime($show_valid_up));
	}

	public function store(Request $request){

		$date = Entry::getPDate();
		$user = Auth::user();

		$check_shift = Entry::checkShift();

		$cre = [
			'name'=>$request->name,
		];

		$rules = [
			'name'=>'required',
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){
			$total_amount = $request->total_amount;
			if($request->id){
				$entry = Recliner::find($request->id);
				if($request->hours_occ <= $entry->hours_occ){
					$data['success'] = false;
					$data['message'] = "Please select valid hours";
					return Response::json($data, 200, []);

				}

				if($request->balance_amount <= 0){
					$data['success'] = false;
					$data['message'] = "Please Referesh your screen and edit Again";

					return Response::json($data, 200, []);
				}

				$message = "Updated Successfully!";

				DB::table('recliner_e_entries')->insert([
					'entry_id' => $entry->id,
					'added_by' => Auth::id(),
					'date' => $date,
					'pay_type' => $request->pay_type,
					
					'paid_amount' => $request->balance_amount,
					'created_at' => date("Y-m-d H:i:s"),
					'current_time' => date("H:i:s"),
					'client_id' => Auth::user()->client_id,
					'type' => 1,
				]);

			} else {
				$entry = new Recliner;
				$message = "Stored Successfully!";
				
				$entry->date = $date;
				$entry->added_by = Auth::id();
				$entry->paid_amount = $total_amount;
				$entry->pay_type = $request->pay_type;
				$entry->slip_id = Recliner::getSlipId();
				$entry->check_in = date("H:i:s");
				$entry->checkin_date = date("Y-m-d H:i:s");
				$entry->client_id = Auth::user()->client_id;
				$entry->shift = $check_shift;
			}

			$entry->name = $request->name;
			$entry->pnr_uid = $request->pnr_uid;
			$entry->mobile_no = $request->mobile_no;	
			
			$entry->hours_occ = $request->hours_occ ? $request->hours_occ : 0;
			$entry->remarks = $request->remarks;
			$entry->unique_id = strtotime('now');

			$entry->rec_ids = implode(',',$request->sl_recliners);

			$entry->save();

			$entry->total_hours = $entry->hours_occ;
			$no_of_min = $request->hours_occ*60;

			$entry->check_out = date("H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->check_in)));
			if($entry->checkin_date){
				$entry->checkout_date = date("Y-m-d H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->checkin_date)));
			}else{
				$check_in_date = $entry->date." ".$entry->check_in;
				$entry->checkout_date = date("Y-m-d H:i:s",strtotime("+".$no_of_min." minutes",strtotime($check_in_date)));
			}
			


			$e_total = Recliner::eSum($entry->id);

			// dd($e_total);

			DB::table('recliners')->whereIn('id',$request->sl_recliners)->update(['status'=>1]);

			$entry->total_amount = $e_total + $entry->paid_amount;
			$barcodevalue = bin2hex($entry->unique_id);
			$entry->barcodevalue = $barcodevalue;
			$entry->max_print = $entry->max_print+1;


			$entry->save();
			$data['id'] = $entry->id;
			$data['print_id'] = $entry->barcodevalue;
			$data['success'] = true;
		} else {
			$data['success'] = false;
			$message = $validator->errors()->first();
		}
		return Response::json($data, 200, []);
	}

	public function checkoutStore(Request $request){
		$date = Entry::getPDate();
		$user = Auth::user();
		$check_shift = Entry::checkShift();
		$cre = [
			'name'=>$request->name,
		];

		$rules = [
			'name'=>'required',
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){
			$entry = Recliner::find($request->id);
			DB::table('e_entries')->insert([
				'entry_id' => $entry->id,
				'added_by' => Auth::id(),
				'date' => $date,
				'pay_type' => $request->pay_type,
				'shift' => $check_shift,
				'paid_amount' => $request->balance_amount,
				'created_at' => date("Y-m-d H:i:s"),
				'current_time' => date("H:i:s"),
				'client_id' => $entry->client_id,
				'type' => 2,
			]);

			$entry->checkout_time = date("Y-m-d H:i:s");
			$entry->checkout_status = 1;
			$entry->checkout_by = Auth::id();
			// $entry->total_amount = $request->total_amount;
			$entry->hours_occ = $request->hours_occ;
			
			$entry->save();
			$entry->total_hours = $entry->hours_occ;

			$e_total = DB::table('e_entries')->where('is_collected',0)->where('is_checked',0)->where('entry_id',$entry->id)->sum('paid_amount');

			$no_of_min = $entry->total_hours*60;

			$check_in_date = $entry->date." ".$entry->check_in;
			$entry->check_out = date("H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->check_in)));
			$entry->checkout_date = date("Y-m-d H:i:s",strtotime("+".$no_of_min." minutes",strtotime($check_in_date)));

			$e_total = Recliner::eSum($entry->id);
			$entry->total_amount = $e_total + $entry->paid_amount;
			$entry->is_late = 1;
			$entry->checkout_th = $request->checkout_th;

			$entry->save();

			$rec_ids = explode(',', $entry->rec_ids);

			DB::table('recliners')->whereIn('id',$rec_ids)->where("client_id", Auth::user()->client_id)->update([
                'status' => 0,
            ]);

			// DB::table('checks')->insert([
			// 	'entry_id' => $entry->id,
			// 	'slip_id' => $entry->slip_id,
			// 	'time' => date("Y-m-d H:i:s"),
			// 	'added_by' => Auth::id(),
			// 	'type' =>2,

			// ]);
			
			$data['id'] = $entry->id;
			$data['print_id'] = $entry->barcodevalue;
			
			$data['success'] = true;
			$data['message'] = "Checkout Successfully";
		} else {
			$data['success'] = false;
			$message = $validator->errors()->first();
		}
		return Response::json($data, 200, []);
	}
	
	
	public function printPost($id = 0){

        $print_data = DB::table('recliners_entries')->where("client_id", Auth::user()->client_id)->where('id', $id)->first();
		$print_data->type = "silip";
        
        $print_data->total_member = $print_data->no_of_adults + $print_data->no_of_children + $print_data->no_of_baby_staff;
        $print_data->adult_amount = 0;
        $print_data->children_amount = 0;
        $hours = $print_data->hours_occ;
        $rate_list = Recliner::rateList();

        $e_total = DB::table('e_entries')->select('paid_amount')->where('is_collected',0)->where('is_checked',0)->where('entry_id', $print_data->id)->sum('paid_amount');

        $total_amount =  $print_data->paid_amount + $e_total;

        if($hours > 0) {
            $print_data->adult_amount = $print_data->no_of_adults * $hours * $rate_list->adult_rate;
            $print_data->children_amount = $print_data->no_of_children * $rate_list->child_rate * $hours;
        }
              
		return view('admin.recliners.print_recliner',compact('print_data','total_amount'));
	}

	public function printPostUnq($type =1,$print_id = ''){
        $print_data = DB::table('recliners_entries')->where('barcodevalue', $print_id)->where("client_id", Auth::user()->client_id)->first();

        if($type == 1 && Auth::user()->priv == 3 && $print_data->print_count >= $print_data->max_print){
			return "Print not allowed";
		}

		$print_data->show_e_ids =  Recliner::getEnos($print_data->rec_ids);

        $hours = $print_data->hours_occ;
        $rate_list = Recliner::rateList();

        $e_total = Recliner::eSum($print_data->id);

        $total_amount =  $print_data->paid_amount + $e_total;
      	
        if($type == 1){
        	DB::table('recliners_entries')->where('id',$print_data->id)->update([
	        	'print_count' => $print_data->print_count+1,
	        ]);
        }
                
		return view('admin.recliners.print_recliner_unq',compact('print_data','total_amount','rate_list','type'));
	}

	public function newCheckout(Request $request,$type=0){
		$client_id = Auth::user()->client_id;

		if($type== 1){
			$entry = Recliner::where("client_id", Auth::user()->client_id)->where("id", $request->checkout_id)->first();
		}else{
			$productName =$request->productName;
    		$entry = Recliner::where("client_id", Auth::user()->client_id)->where('unique_id', $productName)->where("checkout_status", 0)->first();
		}

		if($entry){
			if($entry->checkout_status == 1){
				$data["success"] = true;
				$data["message"] = "Already Checkout!";
			} else {
				$checkout_time = strtotime($entry->checkout_date);
				$current_time = strtotime("now");
				if($current_time < ($checkout_time + 600)){
					$entry->checkout_status = 1;
					$entry->checkout_by = Auth::id();
					$entry->checkout_time = date("Y-m-d H:i:s");
					$entry->checkout_th = $request->checkout_th;
					$entry->save();

					$rec_ids = explode(",", $entry->rec_ids);

					DB::table("recliners")->whereIn("id", $rec_ids)->where("client_id", Auth::user()->client_id)->update(['status' => 0]);

					DB::table('checks')->insert([
						'entry_id' => $entry->id,
						'slip_id' => $entry->slip_id,
						'added_by' => Auth::id(),
						'time' => date("Y-m-d H:i:s"),
						'type' => 1
					]);

					$data['success'] = true;
					$data["message"] = "Successfully Checkout";
				} else {
					$extra_time = $current_time-$checkout_time;
					$extra_time = round($extra_time/60/60, 2);
					$extra_hours = explode(".",$extra_time);
					$ex_hours = $extra_hours[0]*1;
					if($extra_hours[1] > 10){
						$ex_hours += 1;
					}
					$total_hr = $ex_hours+$entry->hours_occ; 

					$e_total = Recliner::eSum($entry->id);
					$rate_list = Recliner::rateList();

					$amount = 0;

					$amount = $amount + ($entry->hours_occ*$rate_list->first_rate );

					$ex_amount = ($entry->hours_occ*$rate_list->second_rate );

					$amount = $amount + ($ex_amount * ($total_hr -1));
					$entry->paid_amount = $entry->paid_amount*1 + $e_total;
					$entry->total_amount = $amount;
					$entry->balance_amount = $amount- $entry->paid_amount;
					$entry->hours_occ = $total_hr;
					$entry->mobile_no = $entry->mobile_no*1;
					$entry->train_no = $entry->train_no*1;
					$entry->pnr_uid = $entry->pnr_uid;

					$entry->show_e_ids = Recliner::getEnos($entry->rec_ids);

					$entry->check_in = date("h:i A",strtotime($entry->check_in));
					$entry->check_out = date("h:i A",strtotime($entry->check_out));
					$entry->checkout_date = date("d-m-Y h:i A",strtotime($entry->checkout_date));

					$entry->show_checkout_date = $this->getValTime($entry->hours_occ,$entry->date,$entry->check_in);

					$data['success'] = false;
					$data['ex_hours'] = $ex_hours;
					$data['entry'] = $entry;
				}
			}

		} else {
			$data["success"] = true;
			$data["message"] = "Data not found!";
		}
		return Response::json($data, 200, []);
	}

	public function checkoutWithoutPenalty($id){
		if(Auth::user()->priv == 2){
			$entry = Recliner::where("checkout_status", "!=", 1)->where('id',$id)->first();
			$entry->is_late = 1;
			$entry->checkout_status = 1;
			$entry->checkout_by = Auth::id();
			$entry->checkout_time = date("Y-m-d H:i:s");
			$entry->save();
		} 

		return Redirect::back();
	}	

	public function changePayType($id){

		$e_entry = DB::table("e_entries")->where("entry_id", $id)->orderBy("id", "DESC")->first();
		if(!$e_entry){
			$entry = Recliner::where("checkout_status", "!=", 1)->where("added_by", Auth::id())->where('id',$id)->first();

			$entry->pay_type = $entry->pay_type == 1 ? 2 : 1;
			$entry->save();
		} else{
			DB::table("e_entries")->where("id", $e_entry->id)->update([
				"pay_type" => $e_entry->pay_type == 1 ? 2 : 1,
			]);
		}
		
		DB::table("change_pay_type_log")->insert([
			"sitting_id"=>$id,
			"old_pay_type"=> $entry->pay_type == 1 ? 2 : 1,
			"new_pay_type"=> $entry->pay_type,
			"e_entry_id"=> $e_entry ? $e_entry->id : 0,
			"changed_by"=> Auth::id(),
			"date"=>date("Y-m-d"),
			"created_at"=>date("Y-m-d H:i:s"),
		]);

		return Redirect::back();
	}

}
