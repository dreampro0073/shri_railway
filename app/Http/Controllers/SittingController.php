<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Entry, App\Models\User, App\Models\Sitting,App\Models\Massage,App\Models\Locker,App\Models\LockerPen;

class SittingController extends Controller {

	public function print1(Request $request){
		// $entries = DB::table('sitting_entries')->select('id','name','pnr_uid','date','check_in','no_of_adults','no_of_children','no_of_baby_staff','paid_amount','print_count');

		$date_ar = [date("Y-m-d",strtotime($request->from_date)), date("Y-m-d",strtotime($request->to_date))];

		$total = DB::table('sitting_entries')
		    ->where('client_id',Auth::user()->client_id)->whereBetween('date',$date_ar)
		    ->count();

		// if($request->has('from_date')){
		// 	$entries = $entries->where('date','>=',date("Y-m-d",strtotime($request->from_date)));
		// }
		// if($request->has('to_date')){
		// 	$entries = $entries->where('date','<=',date("Y-m-d",strtotime($request->to_date)));
		// }

		$final_entries = [];
		foreach ($date_ar as $key => $item_date) {
			$total = DB::table('sitting_entries')
		    ->where('client_id',Auth::user()->client_id)->where('date',$item_date)
		    ->count();
		    $limit = ceil($total * 0.6);

		    $entries = DB::table('sitting_entries')->select('id','name','pnr_uid','date','check_in','no_of_adults','no_of_children','no_of_baby_staff','paid_amount','print_count')->where('client_id',Auth::user()->client_id)->where('date',$item_date)->orderBy('id','DESC')->limit($limit)->get();


		    foreach ($entries as $key => $f_entry) {
		    	$final_entries[] = $f_entry;
		    }
		}

		
		

		
		$str = "<table cellspacing='0' cellpadding='5' border='1'>"; 
		$str .= "<tr>";
		$str .= "<td>SN</td>";
		$str .= "<td>Name</td>";
		$str .= "<td>PNR</td>";
		$str .= "<td>Date</td>";
		$str .= "<td>Check In</td>";
		$str .= "<td>Adults</td>";
		$str .= "<td>Children</td>";
		$str .= "<td>Baby/Staff</td>";
		$str .= "<td>Amount</td>";
		
		$str .= "</tr>";
		$count = 1;

		$date = date("Y-m-d".$request->from_date);



		foreach ($final_entries as $entry) {

			$e_sum = Sitting::eSum($entry->id);
			$entry->paid_amount = $entry->paid_amount+$e_sum;

		    $str .= "<tr>";
		    $str .= "<td>".$count."</td>";
		    $str .= "<td>".$entry->name."</td>";
		    $str .= "<td>".$entry->pnr_uid."</td>";
		    $str .= "<td>".date("d-m-Y",strtotime($entry->date))."</td>";
		    $str .= "<td>".date("h:i A",strtotime($entry->check_in))."</td>";
		    $str .= "<td>".$entry->no_of_adults."</td>";
		    $str .= "<td>".$entry->no_of_children."</td>";
		    $str .= "<td>".$entry->no_of_baby_staff."</td>";
		    $str .= "<td>".$entry->paid_amount."</td>";   
		    $str .= "</tr>";
		    $count++;
		    
		}


		$str .= '</table><br>';
		return $str;
	}

	public function setSlipId(){
		$entries = DB::table('sitting_entries')->where('m_slip',0)->orderBy('id','DESC')->take(500)->get();

		if(sizeof($entries) >0){
			foreach ($entries as $key => $entry) {
				if($entry->slip_id && $entry->slip_id !=''){
					$slip_id = $entry->slip_id;
					$slip_id = substr($slip_id, 1);

					$slip_id = "2".$slip_id;

					DB::table('sitting_entries')->where('id',$entry->id)->update([
						'slip_id' => $slip_id,
						'm_slip' => 1,
					]);
				}
				
			}
			return "Done";
		}else{
			return "All OK";
		}
	}

	public function dumpSittingData(Request $request){
	    return "Wow";
// 		$old_entry_ids = DB::table('users_backup')->pluck('id')->toArray();
// 		foreach ($old_entry_ids as $key => $old_id) {
// 			$newTask = (new User)
// 			->setTable('users_backup')
// 			->find($old_id)
// 			->replicate()
// 			->setTable('users')
// 			->save();
// 		}

		// $old_entry_ids = DB::table('massage_entries_backup')->where('is_backup',0)->take(1000)->pluck('id')->toArray();
		// foreach ($old_entry_ids as $key => $old_id) {
		// 	$newTask = (new Massage)
		// 	->setTable('massage_entries_backup')
		// 	->find($old_id)
		// 	->replicate()
		// 	->setTable('massage_entries')
		// 	->save();

		// 	DB::table('massage_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 		'is_backup' => 1,
		// 	]);
		// }

		// $old_entry_ids = DB::table('locker_entries_backup')->where('is_backup',0)->take(5000)->pluck('id')->toArray();
		// if(sizeof($old_entry_ids) > 0){
		// 	foreach ($old_entry_ids as $key => $old_id) {
		// 		$newTask = (new Locker)
		// 		->setTable('locker_entries_backup')
		// 		->find($old_id)
		// 		->replicate()
		// 		->setTable('locker_entries')
		// 		->save();

		// 		DB::table('locker_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 			'is_backup' => 1,
		// 		]);
		// 	}
		// 	echo 'done';

		// }else{
		// 	echo 'all done';
		// }

		// $old_entry_ids = DB::table('locker_penalty_backup')->where('is_backup',0)->take(1000)->pluck('id')->toArray();
		// if(sizeof($old_entry_ids) > 0){
		// 	foreach ($old_entry_ids as $key => $old_id) {
		// 		$newTask = (new LockerPen)
		// 		->setTable('locker_penalty_backup')
		// 		->find($old_id)
		// 		->replicate()
		// 		->setTable('locker_penalty')
		// 		->save();

		// 		DB::table('locker_penalty_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 			'is_backup' => 1,
		// 		]);
		// 	}
		// 	echo 'done';

		// }else{
		// 	echo 'all done';
		// }

		// $old_entry_ids = DB::table('sitting_entries_backup')->where('is_backup',0)->take(10000)->pluck('id')->toArray();
		// if(sizeof($old_entry_ids) > 0){
		// 	foreach ($old_entry_ids as $key => $old_id) {
		// 		$newTask = (new Sitting)
		// 		->setTable('sitting_entries_backup')
		// 		->find($old_id)
		// 		->replicate()
		// 		->setTable('sitting_entries')
		// 		->save();

		// 		DB::table('sitting_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 			'is_backup' => 1,
		// 		]);
		// 	}
		// 	echo 'done';

		// }else{
		// 	echo 'all done';
		// }		
	}

	public function sitting(Request $request){
		$service_ids = Session::get('service_ids');
		if(in_array(1, $service_ids)){
			return view('admin.sitting.index_new', [
	            "sidebar" => "sitting",
	            "subsidebar" => "sitting",
	        ]);
		} else {
			return view('error');
		}
	}
	public function initEntries(Request $request){
		
		// dd($request->header("apiToken"));
        // $user = User::AuthenticateUser($request->header("apiToken"));
       	// dd($user);


		if(Auth::user()->priv == 2){
			Entry::setCheckStatus();
		}

		$entries = Sitting::select('sitting_entries.*')->where("sitting_entries.client_id", Auth::user()->client_id);
		if($request->slip_id){
			$entries = $entries->where('sitting_entries.slip_id', $request->slip_id);
		}		
		if($request->name){
			$entries = $entries->where('sitting_entries.name', 'LIKE', '%'.$request->name.'%');
		}		
		if($request->mobile_no){
			$entries = $entries->where('sitting_entries.mobile_no', 'LIKE', '%'.$request->mobile_no.'%');
		}		
		if($request->pnr_uid){
			$entries = $entries->where('sitting_entries.pnr_uid', 'LIKE', '%'.$request->pnr_uid.'%');
		}		
		
		
		$entries = $entries->orderBy("checkout_status", 'ASC')->orderBy('id', "DESC");
		$entries = $entries->take(80);
		$entries = $entries->get();
		foreach ($entries as $item) {
			$item->show_time = date("h:i A",strtotime($item->check_in)).' - '.date("h:i A",strtotime($item->check_out));
			$item->show_date = date("d-m-Y",strtotime($item->date));

			$e_total = Sitting::eSum($item->id);

			$item->paid_amount = $item->paid_amount + $e_total;
			$item->str_checkout_time = strtotime($item->checkout_date);
		}
		$rate_list = Sitting::rateList();

		$pay_types = Entry::payTypes();
		$hours = Entry::hours();
		$data['success'] = true;
		$data['entries'] = $entries;
		$data['pay_types'] = $pay_types;
		$data['hours'] = $hours;
		$data['rate_list'] = $rate_list;
		return Response::json($data, 200, []);
	}	
	
	public function editEntry(Request $request){
		$sitting_entry = Sitting::where('id', $request->entry_id)->where("client_id", Auth::user()->client_id)->first();

		if($sitting_entry){
			$sitting_entry->mobile_no = $sitting_entry->mobile_no*1;
			$sitting_entry->train_no = $sitting_entry->train_no*1;
			$sitting_entry->pnr_uid = $sitting_entry->pnr_uid;
			

			$e_total = Sitting::eSum($sitting_entry->id);
			$sitting_entry->paid_amount = $sitting_entry->paid_amount*1 + $e_total;
			$sitting_entry->total_amount = $sitting_entry->paid_amount;

			$sitting_entry->check_in = date("h:i A",strtotime($sitting_entry->check_in));
			$sitting_entry->check_out = date("h:i A",strtotime($sitting_entry->check_out));

			// $sitting_entry->show_valid_up = $this->getValTime($sitting_entry->hours_occ,$sitting_entry->date,$sitting_entry->check_in);
			$sitting_entry->show_valid_up = date("h:i A d-m-Y",strtotime($sitting_entry->checkout_date));
		}

		$data['success'] = true;
		$data['sitting_entry'] = $sitting_entry;
		return Response::json($data, 200, []);
	}
	
	public function checkoutInit(Request $request,$type=0){
		if($type== 1){
			$sitting_entry = Sitting::where('id', $request->entry_id)->where("checkout_status", 0)->where("client_id", Auth::user()->client_id)->first();
		}else{
			$productName =$request->productName;
    		$sitting_entry = Sitting::where('unique_id', $productName)->where("checkout_status", 0)->where("client_id", Auth::user()->client_id)->first();
		}

		if($sitting_entry){
    		$now_time = strtotime(date("Y-m-d H:i:s",strtotime("-10 minutes")));
			$current_time = strtotime(date("Y-m-d H:i:s"));
    		$checkout_time = strtotime($sitting_entry->checkout_date);

			if($checkout_time > $now_time){
				$sitting_entry->checkout_status = 1;
				$sitting_entry->checkout_time = date("Y-m-d H:i:s"); 
				$sitting_entry->checkout_by = Auth::id();
				$sitting_entry->save();

				DB::table('checks')->insert([
					'entry_id' => $sitting_entry->id,
					'slip_id' => $sitting_entry->slip_id,
					'added_by' => Auth::id(),
					'time' => date("Y-m-d H:i:s"),
					'type' => 1
				]);

				$data['success'] = true;
				$data["message"] = "Successfully Checkout";

			}else{
				$extra_time = $current_time-$checkout_time;
				$extra_time = round($extra_time/60/60, 2);
				$extra_hours = explode(".",$extra_time);
				$ex_hours = $extra_hours[0]*1;
				if($extra_hours[1] > 10){
					$ex_hours += 1;
				}
				$sitting_entry->mobile_no = $sitting_entry->mobile_no*1;
				$sitting_entry->train_no = $sitting_entry->train_no*1;
				$sitting_entry->pnr_uid = $sitting_entry->pnr_uid;
				
				$e_total = Sitting::eSum($sitting_entry->id);

				$sitting_entry->paid_amount = $sitting_entry->paid_amount*1 + $e_total;
				$sitting_entry->total_amount = $sitting_entry->paid_amount;
				$sitting_entry->check_in = date("h:i A",strtotime($sitting_entry->check_in));
				$sitting_entry->check_out = date("h:i A",strtotime($sitting_entry->check_out));
				$sitting_entry->checkout_date = date("d-m-Y h:i A",strtotime($sitting_entry->checkout_date));
				$data['success'] = false;
				$data['ex_hours'] = $ex_hours;

				$data['sitting_entry'] = $sitting_entry;
			}
		} else {
			$data['success'] = true;
			$data["message"] = "Already Checkout";
		}
		return Response::json($data, 200, []);
	}

	public function calCheck(Request $request){
		$entry = Sitting::find($request->entry_id);
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
				$entry = Sitting::find($request->id);
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

				DB::table('e_entries')->insert([
					'entry_id' => $entry->id,
					'added_by' => Auth::id(),
					'date' => $date,
					'pay_type' => $request->pay_type,
					'shift' => $check_shift,
					'paid_amount' => $request->balance_amount,
					'created_at' => date("Y-m-d H:i:s"),
					'current_time' => date("H:i:s"),
					'client_id' => Auth::user()->client_id,
					'type' => 1,
				]);

			} else {
				$entry = new Sitting;
				$message = "Stored Successfully!";
				
				$entry->date = $date;
				$entry->added_by = Auth::id();
				$entry->paid_amount = $total_amount;
				$entry->pay_type = $request->pay_type;
				$entry->slip_id = Sitting::getSlipId();
				$entry->check_in = date("H:i:s");
				$entry->checkin_date = date("Y-m-d H:i:s");
				$entry->client_id = Auth::user()->client_id;
				$entry->shift = $check_shift;
			}

			$entry->name = $request->name;
			$entry->pnr_uid = $request->pnr_uid;
			$entry->mobile_no = $request->mobile_no;	
			$entry->no_of_adults = $request->no_of_adults ? $request->no_of_adults : 0;
			$entry->no_of_children = $request->no_of_children ? $request->no_of_children : 0;
			$entry->no_of_baby_staff = $request->no_of_baby_staff ? $request->no_of_baby_staff : 0;
			$entry->hours_occ = $request->hours_occ ? $request->hours_occ : 0;
			$entry->remarks = $request->remarks;
			$entry->unique_id = strtotime('now').Auth::id();

			// dd(strtotime('now').Auth::id());

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
			


			$e_total = Sitting::eSum($entry->id);

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
			$entry = Sitting::find($request->id);
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

			$e_total = Sitting::eSum($entry->id);
			$entry->total_amount = $e_total + $entry->paid_amount;
			$entry->is_late = 1;
			$entry->checkout_th = $request->checkout_th;

			$entry->save();

			DB::table('checks')->insert([
				'entry_id' => $entry->id,
				'slip_id' => $entry->slip_id,
				'time' => date("Y-m-d H:i:s"),
				'added_by' => Auth::id(),
				'type' =>2,

			]);
			
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

        $print_data = DB::table('sitting_entries')->where("client_id", Auth::user()->client_id)->where('id', $id)->first();
		$print_data->type = "silip";
        
        $print_data->total_member = $print_data->no_of_adults + $print_data->no_of_children + $print_data->no_of_baby_staff;
        $print_data->adult_amount = 0;
        $print_data->children_amount = 0;
        $hours = $print_data->hours_occ;
        $rate_list = Sitting::rateList();

        $e_total = DB::table('e_entries')->select('paid_amount')->where('is_collected',0)->where('is_checked',0)->where('entry_id', $print_data->id)->sum('paid_amount');

        $total_amount =  $print_data->paid_amount + $e_total;

        if($hours > 0) {
            $print_data->adult_amount = $print_data->no_of_adults * $hours * $rate_list->adult_rate;
            $print_data->children_amount = $print_data->no_of_children * $rate_list->child_rate * $hours;
        }
              
		return view('admin.sitting.print_sitting',compact('print_data','total_amount'));
	}

	public function printPostUnq($type =1,$print_id = ''){
        $print_data = DB::table('sitting_entries')->where('barcodevalue', $print_id)->where("client_id", Auth::user()->client_id)->first();

        if($type == 1 && Auth::user()->priv == 3 && $print_data->print_count >= $print_data->max_print){
			return "Print not allowed";
		}
		// if($type == 2 && Auth::user()->priv == 3 && $print_data->print_count >= $print_data->max_print){
		// 	return "Print not allowed";
		// }
        
        $print_data->total_member = $print_data->no_of_adults + $print_data->no_of_children + $print_data->no_of_baby_staff;
        $print_data->adult_amount = 0;
        $print_data->children_amount = 0;
        $print_data->adult_s_amount = 0;
        $print_data->children_s_amount = 0;

        $hours = $print_data->hours_occ;
        $rate_list = Sitting::rateList();

        $e_total = Sitting::eSum($print_data->id);

        $total_amount =  $print_data->paid_amount + $e_total;

        $sec_hours = 0;
        $is_sec_rate = false;

        if($rate_list->adult_rate != $rate_list->adult_rate_sec){
        	$is_sec_rate = true;
        }
        $print_data->is_sec_rate = $is_sec_rate;

        if($hours > 0) {
            $print_data->adult_f_amount = $print_data->no_of_adults * 1 * $rate_list->adult_rate;
            $print_data->children_f_amount = $print_data->no_of_children * $rate_list->child_rate * 1;
        }
        
        $sec_hours = $hours - 1;
        if($hours > 1 && $print_data->is_sec_rate) {
        	
            $print_data->adult_s_amount = $print_data->no_of_adults * $sec_hours * $rate_list->adult_rate_sec;
            $print_data->children_s_amount = $print_data->no_of_children * $rate_list->child_rate_sec * $sec_hours;
        }else if($hours > 1){
        	
        	$print_data->adult_f_amount += $print_data->no_of_adults * $sec_hours * $rate_list->adult_rate_sec;
            $print_data->children_f_amount += $print_data->no_of_children * $rate_list->child_rate_sec * $sec_hours;
        }
        if($type == 1){
        	DB::table('sitting_entries')->where('id',$print_data->id)->update([
	        	'print_count' => $print_data->print_count+1,
	        ]);
        }
        
              
		return view('admin.sitting.print_sitting_unq',compact('print_data','total_amount','rate_list','type'));
	}

	public function newCheckout(Request $request,$type=0){
		$client_id = Auth::user()->client_id;

		if($type == 1){
			$entry = Sitting::where("client_id", Auth::user()->client_id)->where("id", $request->checkout_id)->first();
		}else{
			$productName =$request->productName;
    		$entry = Sitting::where("client_id", Auth::user()->client_id)->where('unique_id', $productName)->where("checkout_status", 0)->first();
		}

		if($entry){
			if($entry->checkout_status == 1){
				$data["success"] = true;
				$data["message"] = "Already Checkout!";
			} else {
				$checkout_time = strtotime($entry->checkout_date);
				$current_time = strtotime("now");
				// if($current_time < ($checkout_time + 600)){
				if($current_time < ($checkout_time + 598)){
					$entry->checkout_status = 1;
					$entry->checkout_by = Auth::id();
					$entry->checkout_time = date("Y-m-d H:i:s");
					$entry->checkout_th = $request->checkout_th;
					$entry->save();

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

					$e_total = Sitting::eSum($entry->id);
					$rate_list = Sitting::rateList();

					$amount = 0;
					$amount = $amount + ($entry->no_of_adults*$rate_list->adult_rate ) + ($entry->no_of_children*$rate_list->child_rate);

					$ex_amount = ($entry->no_of_adults*$rate_list->adult_rate_sec ) + ($entry->no_of_children*$rate_list->child_rate_sec);
					$amount = $amount + ($ex_amount * ($total_hr -1));
					$entry->paid_amount = $entry->paid_amount*1 + $e_total;
					$entry->total_amount = $amount;
					$entry->balance_amount = ($amount - $entry->paid_amount)*1;
					$entry->hours_occ = $total_hr;
					$entry->mobile_no = $entry->mobile_no*1;
					$entry->train_no = $entry->train_no*1;
					$entry->pnr_uid = $entry->pnr_uid;

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
			$entry = Sitting::where("checkout_status", "!=", 1)->where('id',$id)->first();
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
			$entry = Sitting::where("checkout_status", "!=", 1)->where("added_by", Auth::id())->where('id',$id)->first();

			$entry->pay_type = $entry->pay_type == 1 ? 2 : 1;
			$entry->save();
		} else{
			DB::table("e_entries")->where("id", $e_entry->id)->update([
				"pay_type" => $e_entry->pay_type == 1 ? 2 : 1,
			]);
		}
		
		DB::table("change_pay_type_log")->insert([
			"sitting_id"=>$id,
			"service_id"=>1,
			"old_pay_type"=> $entry->pay_type == 1 ? 2 : 1,
			"new_pay_type"=> $entry->pay_type,
			"e_entry_id"=> $e_entry ? $e_entry->id : 0,
			"changed_by"=> Auth::id(),
			"date"=>date("Y-m-d"),
			"created_at"=>date("Y-m-d H:i:s"),
		]);

		return Redirect::back();
	}

	public function checkoutAlert(){
        $str_first_time = strtotime("now");
        $str_second_time = $str_first_time + 300;

        $first_time = date("Y-m-d H:i:s", $str_first_time);
        $second_time = date("Y-m-d H:i:s", $str_second_time);
        
        $first_alert = Sitting::where("alert_count", 0)
            ->where("checkout_status", 0)
            ->whereBetween("checkout_date", [$first_time, $second_time])
            ->where("client_id", auth()->user()->client_id)
            ->first();

        if($first_alert){

	        $first_alert->alert_count = $first_alert->alert_count + 1;
	        $first_alert->save();

	        $message  = "Dear ".$first_alert->name." This is a gentle reminder that your slip ID ".$first_alert->slip_id." is due for checkout in 5 minutes. Please take a moment to check out or extend your time. Thank you for your attention and cooperation.

	        	Priya ".$first_alert->name.", Yeh ek vinamra anusmarak hai ki aapki parchi ID ".$first_alert->slip_id." ka checkout 5 minute mein hone wala hai. Kripya samay nikaal kar checkout karein ya apna samay badha lein. Aapke dhyaan aur sahyog ke liye dhanyavaad.";

	        $data['success'] = true;
	        $data['message'] = $message;
	    } else {

	    	$second_alert = Sitting::where("alert_count", '<', 2)
            ->where("checkout_status", 0)
            ->where("checkout_date", '<', $first_time) 
            ->where("client_id", Auth::user()->client_id)
            ->first();

            if($second_alert){
		        $second_alert->alert_count = $second_alert->alert_count + 1;
		        $second_alert->save();

		        // $message  = "Dear ".$second_alert->name." Your slip ID ".$second_alert->slip_id." has exceeded the checkout time. Please check out at your earliest convenience, or your time will be automatically extended. Thank you for your cooperation.  

		        // 	Priya ".$second_alert->name.", Aapki slip ID ".$second_alert->slip_id." ka checkout samay samaapt ho gaya hai. Kripya jaldi se jaldi checkout karein, anyatha aapka samay automatic taur par badha diya jayega. Aapke sahyog ke liye dhanyavaad. ";
		        $message  = "Dear ".$second_alert->name." Your slip ID ".$second_alert->slip_id." has exceeded the checkout time. Please check out at your earliest convenience, or your time will be automatically extended. Thank you for your cooperation.";
		           // $message  = "प्रिय ".$second_alert->name.",
				// 	स्लिप आईडी ".$second_alert->slip_id.", आपका चेकआउट समय समाप्त हो चुका है। कृपया समय से अपना चेकआउट कर लीजिए या अपना बैठने का समय बढ़वा लीजिए। धन्यवाद। dhanyavaad";

		        $data['success'] = true;
		        $data['message'] = $message;
		    } else {
		    	$data["success"] = false;
		    	$data['message'] = "Not Found";
		    }
	    }
	   

        return Response::json($data,200,array());

    }

}
