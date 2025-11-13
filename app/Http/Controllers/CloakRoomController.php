<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Massage, App\Models\User;
use App\Models\Entry;
use App\Models\CloakRoom;
use App\Models\CollectedPenalities;

class CloakRoomController extends Controller {	
	public function index(){
		return view('admin.cloakrooms.index', [
            "sidebar" => "cloakrooms",
            "subsidebar" => "cloakrooms",
            "type" => 0,
        ]);
	}
	public function allRooms(){
		return view('admin.cloakrooms.index', [
            "sidebar" => "all-cloakrooms",
            "subsidebar" => "all-cloakrooms",
            "type" => 1,
        ]);
	}
	public function printBegs(){

		$l_entries = DB::table('cloakroom_entries')->select('slip_id','no_of_bag')->where("cloakroom_entries.client_id", Auth::user()->client_id)->where('checkout_status',0)->get();
		return view('admin.cloakrooms.print_begs', [
            "sidebar" => "cloakrooms",
            "subsidebar" => "cloakrooms",
            "l_entries" => $l_entries,
        ]);
	}
	public function initRoom(Request $request, $type = 0){
	    ini_set('max_execution_time', 300);
	    ini_set('memory_limit', '512M');

	    $max_per_page = 100;
	    $page_no = $request->page_no ?? 1;
	    $client_id = Auth::user()->client_id;

	    DB::disconnect('mysql');
	    DB::reconnect('mysql');
	    if (Auth::user()->priv == 2) {
	        CollectedPenalities::setCheckStatus();
	    }

	    $query = DB::table('cloakroom_entries')
	        ->select('cloakroom_entries.*')->where('cloakroom_entries.client_id', $client_id);

	    if ($request->filled('slip_id')) {
	        $query->where('cloakroom_entries.slip_id', $request->slip_id);
	    }

	    if ($request->filled('unique_id')) {
	        $query->where('cloakroom_entries.unique_id', 'LIKE', '%'.$request->unique_id.'%');
	    }

	    if ($request->filled('name')) {
	        $query->where('cloakroom_entries.name', 'LIKE', '%'.$request->name.'%');
	    }

	    if ($request->filled('mobile_no')) {
	        $query->where('cloakroom_entries.mobile_no', 'LIKE', '%'.$request->mobile_no.'%');
	    }

	    if ($request->filled('pnr_uid')) {
	        $query->where('cloakroom_entries.pnr_uid', 'LIKE', '%'.$request->pnr_uid.'%');
	    }

	    if ($type == 0) {
	        $query->where('cloakroom_entries.checkout_status', 0);
	    }

	    if ($type == 1) {
	        $query->skip(($page_no - 1) * $max_per_page)->take($max_per_page);
	    }

	    if ($request->has('export') && $request->export == 1) {
	        $from = date("Y-m-d", strtotime($request->from_date));
	        $to   = date("Y-m-d", strtotime($request->to_date));
	        $query->whereBetween('date', [$from, $to]);
	    }

	    $l_entries = $query->orderByDesc('cloakroom_entries.id')->get();

	    foreach ($l_entries as $item) {
	        DB::reconnect('mysql');

	        $bm_amount = DB::table('cloakroom_penalities')
	            ->where('client_id', $client_id)
	            ->where('is_collected', 0)
	            ->where('cloakroom_id', $item->id)
	            ->sum('paid_amount');

	        $item->sh_paid_amount = $item->paid_amount + $bm_amount;
	        $item->checkin_date_show = date("d M, h:i A", strtotime($item->checkin_date));
	        $item->checkout_date_show = date("d M, h:i A", strtotime($item->checkout_date));
	        $item->str_checkout_time = strtotime($item->checkout_date);
	    }


	    if ($request->has('export') && $request->export == 1 && sizeof($l_entries) > 0) {
	        include(app_path().'/Excel/export_entries.php');
	        $data["excel_link"] = url('temp/'.$filename);
	    }

	    DB::reconnect('mysql');
	    $rate_list = DB::table('cloakroom_rate_list')
	        ->where('client_id', $client_id)
	        ->first();

	    $data['success'] = true;
	    $data['l_entries'] = $l_entries;
	    $data['pay_types'] = Entry::payTypes();
	    $data['rate_list'] = $rate_list;
	    $data['days'] = Entry::days();
	    $data['show_pay_types'] = Entry::showPayTypes();
	    $data['users'] = User::where('active', 1)
	        ->where('client_id', $client_id)
	        ->where('priv', 3)
	        ->pluck('name', 'id')
	        ->toArray();

	    return response()->json($data, 200);
	}

	// public function initRoom(Request $request,$type =0){
	// 	$max_per_page = 10;
	// 	$page_no = $request->page_no;

	// 	if(Auth::user()->priv == 2){
	// 		CollectedPenalities::setCheckStatus();
	// 	}

	// 	$l_entries = DB::table('cloakroom_entries')->select('cloakroom_entries.*','aadhar_details.front as aadhar_front','aadhar_details.back as aadhar_back')->leftJoin("aadhar_details","aadhar_details.aadhar_no", "=" ,"cloakroom_entries.aadhar_no")->where("cloakroom_entries.client_id", Auth::user()->client_id);
	// 	if($request->slip_id){
	// 		$l_entries = $l_entries->where('cloakroom_entries.slip_id', $request->slip_id);
	// 	}

	// 	if($request->unique_id){
	// 		$l_entries = $l_entries->where('cloakroom_entries.unique_id', 'LIKE', '%'.$request->unique_id.'%');
	// 	}		

	// 	if($request->name){
	// 		$l_entries = $l_entries->where('cloakroom_entries.name', 'LIKE', '%'.$request->name.'%');
	// 	}		
	// 	if($request->mobile_no){
	// 		$l_entries = $l_entries->where('cloakroom_entries.mobile_no', 'LIKE', '%'.$request->mobile_no.'%');
	// 	}		
	// 	if($request->pnr_uid){
	// 		$l_entries = $l_entries->where('cloakroom_entries.pnr_uid', 'LIKE', '%'.$request->pnr_uid.'%');
	// 	}		
		
	// 	if($type == 0){
	// 		$l_entries = $l_entries->where('cloakroom_entries.checkout_status', 0);
			
	// 	}
	// 	if($type == 1){
	// 		$l_entries = $l_entries->skip(($page_no-1)*$max_per_page)->take($max_per_page);
	// 	}
	// 	if($request->has('export') && $request->export == 1){
	// 		$dt_ar = [date("Y-m-d",strtotime($request->from_date)),date("Y-m-d",strtotime($request->to_date))];
	// 		$l_entries = $l_entries->whereBetween('date',$dt_ar);
	// 	}
	// 	$l_entries = $l_entries->orderBy('cloakroom_entries.id', "DESC")->get();
	// 	foreach ($l_entries as $key => $item) {
	// 		$bm_amount = DB::table('cloakroom_penalities')->where("client_id", Auth::user()->client_id)->where('is_collected',0)->where('cloakroom_id','=',$item->id)->sum('paid_amount');
	// 		$item->sh_paid_amount = $item->paid_amount + $bm_amount;
	// 		$item->checkin_date_show = date("d M, h:i A",strtotime($item->checkin_date));
	// 		$item->checkout_date_show = date("d M, h:i A",strtotime($item->checkout_date));

	// 		$item->str_checkout_time = strtotime($item->checkout_date);
	// 	}
		

	// 	if($request->has('export') && $request->export == 1){
    //         if(sizeof($l_entries) > 0){
    //             include(app_path().'/Excel/export_entries.php');
    //             $data["excel_link"] = url('temp/'.$filename);
    //         }
    //     }
	// 	$rate_list = DB::table("cloakroom_rate_list")->where("client_id", Auth::user()->client_id)->first();
	// 	$pay_types = Entry::payTypes();

	// 	$days = Entry::days();

	// 	$show_pay_types = Entry::showPayTypes();
        
	// 	$data['success'] = true;
	// 	$data['l_entries'] = $l_entries;
	// 	$data['pay_types'] = $pay_types;
	// 	$data['rate_list'] = $rate_list;
	// 	$data['days'] = $days;
	// 	$data["users"] = User::where("active", 1)->where("client_id", Auth::user()->client_id)->where("priv", 3)->pluck("name", 'id')->toArray();
		
	// 	return Response::json($data, 200, []);
	// }

	public function export(){
		return view('admin.cloakrooms.export', [
            "sidebar" => "export",
            "subsidebar" => "export",
            "type" => 0,
        ]);
	}
	

	public function editRoom(Request $request){
		$l_entry = CloakRoom::where('id', $request->entry_id)->where("client_id", Auth::user()->client_id)->first();

		if($l_entry){
			$l_entry->mobile_no = $l_entry->mobile_no*1;
			$l_entry->train_no = $l_entry->train_no*1;
			$l_entry->pnr_uid = $l_entry->pnr_uid;
			$l_entry->paid_amount = $l_entry->paid_amount*1;
			$l_entry->check_in = date("d-m-Y",strtotime($l_entry->date))." ".date("h:i A",strtotime($l_entry->check_in));
			$l_entry->check_out = date("d-m-Y h:i A",strtotime($l_entry->checkout_date));
			$bm_amount = DB::table('cloakroom_penalities')->where('cloakroom_id','=',$l_entry->id)->sum('paid_amount');
			$l_entry->paid_amount = $l_entry->paid_amount + $bm_amount;

			$l_entry->bm_amount = $bm_amount;
		}

		$data['success'] = true;
		$data['l_entry'] = $l_entry;
		return Response::json($data, 200, []);
	}
	
	public function store(Request $request){
		$check_shift = Entry::checkShift();
		$cre = [
			'name'=>$request->name,
		];

		$rules = [
			'name'=>'required',
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){
			
			$date = Entry::getPDate();
			$balance_amount = $request->has('balance_amount')?$request->balance_amount:0;
			$paid_amount = $request->paid_amount;
			if($request->id){
				$group_id = $request->id;
				$entry = CloakRoom::where('id', $request->id)->where("client_id", Auth::user()->client_id)->first();
				$message = "Updated Successfully!";
				$entry->check_in = date("H:i:s",strtotime($request->check_in));
				
				DB::table('cloakroom_penalities')->insert([
					'cloakroom_id' => $entry->id,
					'paid_amount' => $balance_amount,
					'pay_type' => $request->pay_type,
					'shift' => $check_shift,
					'date' =>$date,
					'added_by' =>Auth::id(),
					'client_id' =>Auth::user()->client_id,
					'current_time' => date("H:i:s"),
					'created_at' => date('Y-m-d H:i:s'),
				]);

			} else {
				$entry = new CloakRoom;
				$message = "Stored Successfully!";
				$entry->unique_id = strtotime('now');
				
				$entry->created_at = date("Y-m-d H:i:s");
				$entry->checkin_date = date("Y-m-d H:i:s");
				$entry->check_in = date("H:i:s");
				$entry->date = date("Y-m-d");
				$entry->shift = $check_shift;
				$entry->added_by = Auth::id();
				$entry->paid_amount = $request->paid_amount;
				$entry->aadhar_no = $request->has('aadhar_no')?$request->aadhar_no:null;

				$entry->slip_id = CloakRoom::getSlipId();
				
				DB::table("aadhar_details")->where("aadhar_no", $request->aadhar_no)->update([
					"name"=>$request->name,
					"front"=>$request->aadhar_front,
					"back"=>$request->aadhar_back,
					"mobile"=>$request->mobile_no,
					"upload_status"=>2,
				]);
			}

			$entry->name = $request->name;
			$entry->no_of_bag = $request->no_of_bag;
			$entry->total_bag = $request->no_of_bag;
			$entry->pnr_uid = $request->pnr_uid;
			$entry->mobile_no = $request->mobile_no;
			$entry->no_of_day = $request->no_of_day;
			$entry->total_day = $request->no_of_day;
			$entry->pay_type = $request->pay_type;
			$entry->remarks = $request->remarks;
			$entry->paid_amount = $request->paid_amount;
			$entry->client_id = Auth::user()->client_id;
			$entry->save();

			$entry->barcodevalue = bin2hex($entry->unique_id);
			$hours = $entry->no_of_day * 24;

			$checkout_date = date("Y-m-d H:i:s",strtotime("+".$hours.' hours',strtotime($entry->check_in)));
	        $entry->checkout_date = $checkout_date;
	        
			$entry->total_amount = $entry->paid_amount+$balance_amount;
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


	public function printPost($id = 0){

		$rate_list = DB::table("cloakroom_rate_list")->where("client_id", Auth::user()->client_id)->first();
        $print_data = DB::table('cloakroom_entries')->where("client_id", Auth::user()->client_id)->where('id', $id)->first();

        $total_amount = $print_data->paid_amount;

        $bm_amount = DB::table('cloakroom_penalities')->where('cloakroom_id','=',$print_data->id)->where('is_collected',1)->sum('paid_amount');

        $total_amount = $total_amount+$bm_amount;
       
        $print_data->for_first_day = 0;
        $print_data->for_other_day = 0;        
        $total_day = $print_data->total_day - 1;
        
        if($print_data->total_day > 0) {
            $print_data->for_first_day = $print_data->no_of_bag * $rate_list->first_rate;       
        }           
        if($total_day > 0){
            $print_data->for_other_day = $print_data->no_of_bag * $total_day * $rate_list->second_rate;

        }
        $rate_list = DB::table("cloakroom_rate_list")->where("client_id", Auth::user()->client_id)->first();
        $type = 0;
        if(User::checkHrType()){
			return view('admin.cloakrooms.print_page_cloack_unq_1',compact('print_data','total_amount','rate_list','type'));
		}else{
			return view('admin.cloakrooms.print_page_cloack',compact('print_data','total_amount','rate_list'));
		}
	}

	public function printPostUnq($type= 1,$print_id = ''){
		$print_data = DB::table('cloakroom_entries')->where('barcodevalue','=',$print_id)->where('client_id',Auth::user()->client_id)->first();

		if (!$print_data) {
            return view('admin.sitting.print_blocked', [
                'message' => 'Slip not found.'
            ]);
        }

		// if($type == 1 && Auth::user()->priv == 3 && $print_data->print_count > 0){
		// 	return "Print not allowed";
		// }

		$rate_list = DB::table("cloakroom_rate_list")->where("client_id", Auth::user()->client_id)->first();
        $print_data = DB::table('cloakroom_entries')->where("client_id", Auth::user()->client_id)->where('barcodevalue', $print_id)->first();

        $total_amount = $print_data->paid_amount;

        $bm_amount = DB::table('cloakroom_penalities')->where('cloakroom_id','=',$print_data->id)->where('is_collected',1)->sum('paid_amount');

        $total_amount = $total_amount+$bm_amount;
       
        $print_data->for_first_day = 0;
        $print_data->for_other_day = 0;        
        $total_day = $print_data->total_day - 1;
        
        if($print_data->total_day > 0) {
            $print_data->for_first_day = $print_data->no_of_bag * $rate_list->first_rate;       
        }           
        if($total_day > 0){
            $print_data->for_other_day = $print_data->no_of_bag * $total_day * $rate_list->second_rate;

        }
        $rate_list = DB::table("cloakroom_rate_list")->where("client_id", Auth::user()->client_id)->first();

        if(Auth::user()->priv == 3){
        	DB::table('cloakroom_entries')->where('id',$print_data->id)->update([
        		'print_count' => $print_data->print_count+1,
        	]);
        }
        if(User::checkHrType()){
			return view('admin.cloakrooms.print_page_cloack_unq_1',compact('print_data','total_amount','rate_list','type'));
		}else{
			return view('admin.cloakrooms.print_page_cloack_unq',compact('print_data','total_amount','rate_list','type'));
		}
	}

   	public function checkoutInit(Request $request,$type=0){
    	$now_time = strtotime(date("Y-m-d H:i:s",strtotime("-15 minutes")));

    	if($type == 1){
    		$l_entry = CloakRoom::where('id', $request->entry_id)->where("client_id", Auth::user()->client_id)->where('checkout_status',0)->first();

    	}else{
    		$productName =$request->productName;
	    	$l_entry = CloakRoom::where('unique_id', $productName)->where("client_id", Auth::user()->client_id)->where('checkout_status',0)->first();
    	}

    	// dd($l_entry);
    	if($l_entry){
    		$entry_id = $l_entry->id;

	    	$checkout_time = strtotime($l_entry->checkout_date);
	    	$rate_list = DB::table("cloakroom_rate_list")->where("client_id", Auth::user()->client_id)->first();

	    	if($checkout_time > $now_time){
    			$data['timeOut'] = false;
	    		$l_entry = CloakRoom::where('id',$entry_id)->where("client_id", Auth::user()->client_id)->first();
	    		$l_entry->status = 1; 
				$l_entry->checkout_status = 1;
	    		$l_entry->checkout_by = Auth::id();
	    		$l_entry->checkout_time = date("Y-m-d H:i:s"); 
	    		$l_entry->save();
	    		$data['success'] = true;
	    		$data['message'] = "Successfully Checkout";
	    	} else {
	    		$current_time = strtotime(date("Y-m-d H:i:s",strtotime("now")));
	    		$time_difference = $current_time - $checkout_time;
	    		$day = floor($time_difference / 86400);
	    		$hours = floor(($time_difference % 86400) / 3600);
				$minutes = floor(($time_difference % 3600) / 60);

				if (User::checkHrType()) {
				    if ($hours > 12 || ($hours == 12 && $minutes >= 15)) {
				        $day += 1; 
				    } elseif ($hours <= 12 && ($hours > 0 || $minutes >= 15)) {
				        $day += 0.5; 

				    }
				} else {
				    if ($hours > 0 || $minutes > 15) {
				        $day += 1; 
				    }
				}

				$l_entry->mobile_no = $l_entry->mobile_no*1;
				$l_entry->train_no = $l_entry->train_no*1;
				$l_entry->pnr_uid = $l_entry->pnr_uid*1;

				$l_entry->paid_amount = $l_entry->paid_amount*1;

				$l_entry->balance = $day* $rate_list->second_rate *$l_entry->no_of_bag;

				$l_entry->total_balance = $l_entry->paid_amount+$l_entry->balance;
				$l_entry->day = $day;
				$l_entry->final_days = $day + $l_entry->no_of_day;

				
				$data['l_entry'] = $l_entry;
				$data['success'] = true;
				$data['timeOut'] = true;
			}
    	}else{
    		$data['success'] = true;
	    	$data['message'] = "All Ready checkout";
    	}

		return Response::json($data, 200, []);
    }

    public function checkoutStore(Request $request){
    	$check_shift = Entry::checkShift();
    	$entry = CloakRoom::where('id',$request->id)->where("client_id", Auth::user()->client_id)->first();
    	if(User::checkHrType()){
    		$total_day =  $entry->no_of_day + $request->day;
    	} else {
    		$total_day =  $entry->total_day + $request->day;
    	}
		$entry->status = 1; 
		$entry->checkout_status = 1;
		$entry->is_late = 1;
		$entry->total_day = $total_day;
		$entry->penality = $request->balance;
		$entry->checkout_time = date('Y-m-d H:i:s');
		if(isset($request->checkout_by) && $request->checkout_by > 0){ 
			$checkout_by = $request->checkout_by;
		} else {
			$checkout_by = Auth::id();
		}
		$entry->checkout_by = $checkout_by;

		$entry->total_amount = $request->balance+$entry->paid_amount;

		$entry->save();

		$date = Entry::getPDate();

		DB::table('cloakroom_penalities')->insert([
			'cloakroom_id' => $entry->id,
			'silip_id' => $entry->slip_id,
			'paid_amount' => $request->balance,
			'pay_type' => $request->pay_type,
			'shift' => $check_shift,
			'date' =>$date,
			'added_by' => $checkout_by,
			'client_id' =>Auth::user()->client_id,
			'current_time' => date("H:i:s"),
			'created_at' => date('Y-m-d H:i:s'),
		]);
		$data['success'] = true;
		$data['id'] = $request->id;

		// $data['print_id'] = $entry->barcodevalue;
		return Response::json($data, 200, []);
    }
    function getNameFromNumber($num) {
        $numeric = ($num ) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num ) / 26) - 1;
        if ($num2 >= 0) {
            return $this->getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }

    public function storePen(Request $request){
    	CollectedPenalities::penltyCollection($request->id);

    	$data['success'] = true;
    	$data['message'] = 'Successfully';

		return Response::json($data, 200, []);

    }
}
