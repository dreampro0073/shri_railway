<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Massage, App\Models\User;
use App\Models\Entry;
use App\Models\Locker;

class LockerController extends Controller {	
	public function index(){
		return view('admin.locker.index', [
            "sidebar" => "locker",
            "subsidebar" => "locker",
        ]);
	}
	
	public function initLocker(Request $request){
		$client_id = Auth::user()->client_id;
		$l_entries = DB::table('locker_entries')->select('locker_entries.*')->where("locker_entries.client_id", $client_id);
		if($request->unique_id){
			$l_entries = $l_entries->where('locker_entries.unique_id', 'LIKE', '%'.$request->unique_id.'%');
		}
		if($request->name){
			$l_entries = $l_entries->where('locker_entries.name', 'LIKE', '%'.$request->name.'%');
		}		
		if($request->mobile_no){
			$l_entries = $l_entries->where('locker_entries.mobile_no', 'LIKE', '%'.$request->mobile_no.'%');
		}		
		if($request->pnr_uid){
			$l_entries = $l_entries->where('locker_entries.pnr_uid', 'LIKE', '%'.$request->pnr_uid.'%');
		}		
		
		
		$l_entries = $l_entries->where('checkout_status', 0);
		$l_entries = $l_entries->orderBy('id', "DESC")->get();

		foreach ($l_entries as $key => $item) {
			$bm_amount = DB::table('locker_penalty')->where("locker_penalty.client_id", $client_id)->where('locker_entry_id','=',$item->id)->sum('paid_amount');
			$item->checkout_date = date("d-m-Y H:i:s", strtotime($item->checkout_date));
			$item->checkin_date = date("d-m-Y H:i:s", strtotime($item->checkin_date));
			$item->sh_paid_amount = $item->paid_amount + $bm_amount;
		}

		$pay_types = Entry::payTypes();
		$days = Entry::days();
		$show_pay_types = Entry::showPayTypes();
		$avail_lockers = Entry::getAvailLockers();

		$data['success'] = true;
		$data['l_entries'] = $l_entries;
		$data['pay_types'] = $pay_types;
		$data['days'] = $days;
		$data['avail_lockers'] = $avail_lockers;

		return Response::json($data, 200, []);
	}
	public function editLocker(Request $request){
		$client_id = Auth::user()->client_id;
		$l_entry = Locker::where('id', $request->entry_id)->where("client_id", $client_id)->first();

		$sl_lockers = [];

		if($l_entry){
			$l_entry->mobile_no = $l_entry->mobile_no*1;
			$l_entry->train_no = $l_entry->train_no*1;
			$l_entry->pnr_uid = $l_entry->pnr_uid;
			$l_entry->paid_amount = $l_entry->paid_amount*1;
			$l_entry->check_in = date("d-m-Y",strtotime($l_entry->date))." ".date("h:i A",strtotime($l_entry->check_in));
			$l_entry->check_out = date("d-m-Y h:i A",strtotime($l_entry->checkout_date));
			$sl_lockers = explode(',', $l_entry->locker_ids);


			$bm_amount = DB::table('locker_penalty')->where("client_id", $client_id)->where('locker_entry_id','=',$l_entry->id)->sum('paid_amount');
			$l_entry->paid_amount = $l_entry->paid_amount + $bm_amount;

			$l_entry->bm_amount = $bm_amount;
		}

		$data["rate_list"] = DB::table("locker_rate_list")->where("client_id", $client_id)->first();

		$data['success'] = true;
		$data['l_entry'] = $l_entry;
		$data['sl_lockers'] = $sl_lockers;
		return Response::json($data, 200, []);
	}
	public function calCheck(Request $request){
		
		$check_in = $request->check_in;
		$no_of_day = $request->no_of_day;

		$hours = 24*$no_of_day;
		$ss_time = strtotime(date("h:i A",strtotime($check_in)));
		$new_time = date("h:i A", strtotime('+'.$hours.' hours', $ss_time));

		$data['success'] = true;
		$data['check_out'] = $new_time;
		return Response::json($data, 200, []);
	}

	public function store(Request $request){
		$client_id = Auth::user()->client_id;
		$check_shift = Entry::checkShift();
		$user_id = Auth::id();

		$cre = [
			'name'=>$request->name,
		];

		$rules = [
			'name'=>'required',
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){
			$date = Entry::getPDate();
			$balance_amount = $request->balance_amount;
			$paid_amount = $request->paid_amount;
			if($request->id){
				$group_id = $request->id;
				$entry = Locker::where("client_id", $client_id)->find($request->id);
				$message = "Updated Successfully!";
				$entry->check_in = date("H:i:s",strtotime($request->check_in));
				

				DB::table('locker_penalty')->insert([
					'locker_entry_id' => $entry->id,
					'paid_amount' => $balance_amount,
					'pay_type' => $request->pay_type,
					'shift' => $check_shift,
					'date' =>$date,
					'added_by' =>Auth::id(),
					'client_id' =>$client_id,
					'current_time' => date("H:i:s"),
					'created_at' => date('Y-m-d H:i:s'),
				]);

			} else {
				$entry = new Locker;
				$message = "Stored Successfully!";
				$entry->unique_id = strtotime('now');
				$entry->created_at = date("Y-m-d H:i:s");
				$entry->checkin_date = date("Y-m-d H:i:s");
				$entry->check_in = date("H:i:s");
				$entry->date = date("Y-m-d");
				$entry->shift = $check_shift;
				$entry->added_by = Auth::id();
				$entry->paid_amount = $request->paid_amount;

			}

			$entry->client_id = $client_id;
			$entry->name = $request->name;
			$entry->nos = $request->nos;
			$entry->pnr_uid = $request->pnr_uid;
			$entry->mobile_no = $request->mobile_no;
			$entry->no_of_day = $request->no_of_day;
			$entry->pay_type = $request->pay_type;
			$entry->remarks = $request->remarks;
			$entry->locker_ids = implode(',',$request->sl_lockers);
			$entry->save();

			$checkout_date = date("Y-m-d H:i:s",strtotime("+".$entry->no_of_day.' day',strtotime($entry->check_in)));
	        $entry->checkout_date = $checkout_date;


			$entry->save();

			DB::table('lockers')->whereIn('id',$request->sl_lockers)->where("client_id", $client_id)->update([
				'status' => 1,
			]);
			
			$data['id'] = $entry->id;
			$data['success'] = true;
		} else {
			$data['success'] = false;
			$message = $validator->errors()->first();
		}

		return Response::json($data, 200, []);

	}

	public function printPost($id = 0){
		$client_id = Auth::user()->client_id;
        $print_data = DB::table('locker_entries')->where("client_id", $client_id)->where('id', $id)->first();

        $bm_amount = DB::table('locker_penalty')->where('locker_entry_id','=',$print_data->id)->where("client_id", $client_id)->sum('paid_amount');
			$print_data->paid_amount = $print_data->paid_amount + $bm_amount;

			$print_data->bm_amount = $bm_amount;

        return view('admin.print_page_locker', compact('print_data'));
	}

    public function checkoutInit(Request $request){
    	$client_id = Auth::user()->client_id;
    	$now_time = strtotime(date("Y-m-d H:i:s",strtotime("+5 minutes")));

    	$l_entry = Locker::where('id', $request->entry_id)->where("client_id", $client_id)->first();
    	
    	if(!$request->freePenalty){
    		$checkout_time = strtotime($l_entry->checkout_date);
    		if($checkout_time > $now_time){
    			$penality = false;
    		} else {
    			$penality = true;	
    		}
    	} else {
    		$penality = false;
    	}

    	if(!$penality){
    		$data['timeOut'] = false;
    		$entry = Locker::where("client_id", $client_id)->find($request->entry_id);
    		$entry->status = 1; 
    		$entry->checkout_status = 1; 
    		$entry->save();
    		$data['success'] = true;

			$locker_ids = explode(',', $l_entry->locker_ids);

    		DB::table('lockers')->where("client_id", $client_id)->whereIn('id',$locker_ids)->update(['status'=>0]);
    
    	} else {
    		$extra_time = round($now_time - $checkout_time)/(60 * 60 * 24);
			$extra_days = explode(".",$extra_time);
			$day = 0;
			$day = $extra_days[0]*1;
			if($extra_days[1] > 10){
				$day += 1;
			}

    		// $locker_ids = explode(',', $request->locker_ids);
    		$locker_ids = explode(',', $l_entry->locker_ids);

			$l_entry->mobile_no = $l_entry->mobile_no*1;
			$l_entry->train_no = $l_entry->train_no*1;
			$l_entry->pnr_uid = $l_entry->pnr_uid*1;
			$l_entry->paid_amount = $l_entry->paid_amount*1;
			$l_entry->balance = $day*70*sizeof($locker_ids);
			$l_entry->total_balance = $l_entry->paid_amount+$l_entry->balance;
			$l_entry->day = $day;

			
			$data['l_entry'] = $l_entry;
			$data['success'] = true;
			$data['timeOut'] = true;
		}

		return Response::json($data, 200, []);
    }

    public function checkoutStore(Request $request){
    	$client_id = Auth::user()->client_id;
    	$check_shift = Entry::checkShift();
    	$entry = Locker::where("client_id", $client_id)->find($request->id);

		$entry->status = 1; 
		$entry->checkout_status = 1;
		$entry->penality = $request->balance;
		$entry->checkout_date = date('Y-m-d H:i:s'); 
		$entry->save();

		$date = Entry::getPDate();


		DB::table('locker_penalty')->insert([
			'locker_entry_id' => $entry->id,
			'paid_amount' => $request->balance,
			'pay_type' => $request->pay_type,
			'shift' => $check_shift,
			'date' =>$date,
			'added_by' =>Auth::id(),
			'current_time' => date("H:i:s"),
			'created_at' => date('Y-m-d H:i:s'),
			'client_id' => $client_id,
		]);

		$locker_ids = explode(',', $request->locker_ids);

		DB::table('lockers')->where("client_id", $client_id)->whereIn('id',$locker_ids)->update(['status'=>0]);
		$data['success'] = true;
		return Response::json($data, 200, []);
    }
    
    public function delete($id){
    	DB::table('locker_entries')->where("client_id", $client_id)->where('id',$id)->update([
    		'deleted' => 1,
    		'delete_by' => Auth::id(),
    		'delete_time' => date("Y-m-d H:i:s"),
    	]);

    	$data['success'] = true;
    	$data['message'] = "Successfully";

		return Response::json($data, 200, []);
	}


}
