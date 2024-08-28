<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Entry, App\Models\User, App\Models\Sitting;

class SittingControllerOld extends Controller {
	public function sitting(Request $request){
		$service_ids = Session::get('service_ids');
		if(in_array(1, $service_ids)){
			return view('admin.sitting.index_new', [
	            "sidebar" => "sitting",
	            "subsidebar" => "sitting",
	        ]);
		} else {
			die("Not authorized!");
		}
	}
	public function initEntries(Request $request){
		$entries = Sitting::select('sitting_entries.*','users.name as username')->leftJoin('users','users.id','=','sitting_entries.delete_by')->where("sitting_entries.client_id", Auth::user()->client_id);
		if($request->slip_id){
			$entries = $entries->where('sitting_entries.slip_id', $request->slip_id);
		}		
		if($request->unique_id){
			$entries = $entries->where('sitting_entries.unique_id', 'LIKE', '%'.$request->unique_id.'%');
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
		
		if(Auth::user()->priv != 1){
			$entries = $entries->where('deleted',0);

		}
		$entries = $entries->orderBy("checkout_status", 'ASC')->orderBy('id', "DESC");

		// if(Auth::user()->priv != 1){

		// }
		$entries = $entries->take(50);
		$entries = $entries->get();
		foreach ($entries as $item) {
			$item->show_time = date("h:i A",strtotime($item->check_in)).' - '.date("h:i A",strtotime($item->check_out));
		}
		$rate_list = DB::table("sitting_rate_list")->where("client_id", Auth::user()->client_id)->first();

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
		$sitting_entry = Sitting::where('id', $request->entry_id)->first();

		if($sitting_entry){
			$sitting_entry->mobile_no = $sitting_entry->mobile_no*1;
			$sitting_entry->train_no = $sitting_entry->train_no*1;
			$sitting_entry->pnr_uid = $sitting_entry->pnr_uid;
			$sitting_entry->paid_amount = $sitting_entry->paid_amount*1;
			$sitting_entry->total_amount = $sitting_entry->paid_amount*1;
			$sitting_entry->check_in = date("h:i A",strtotime($sitting_entry->check_in));
			$sitting_entry->check_out = date("h:i A",strtotime($sitting_entry->check_out));
		}

		$data['success'] = true;
		$data['sitting_entry'] = $sitting_entry;
		return Response::json($data, 200, []);
	}
	
	public function checkout(Request $request){
		$sitting_entry = Sitting::where('id', $request->entry_id)->where("checkout_status", 0)->first();

		if($sitting_entry){
			// $now_time = strtotime(date("Y-m-d H:i:s",strtotime("+5 minutes")));
    		$now_time = strtotime(date("Y-m-d H:i:s",strtotime("-10 minutes")));

			$current_time = strtotime(date("Y-m-d H:i:s"));
    		$checkout_time = strtotime($sitting_entry->checkout_date);

			if($checkout_time > $now_time){
				$sitting_entry->checkout_status = 1;
				$sitting_entry->checkout_time = date("Y-m-d H:i:s"); 
				$sitting_entry->checkout_by = Auth::id();
				$sitting_entry->save();
				$data['success'] = true;
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
				$sitting_entry->paid_amount = $sitting_entry->paid_amount*1;
				$sitting_entry->total_amount = $sitting_entry->paid_amount*1;
				$sitting_entry->check_in = date("h:i A",strtotime($sitting_entry->check_in));
				$sitting_entry->check_out = date("h:i A",strtotime($sitting_entry->check_out));
				$data['success'] = false;
				$data['ex_hours'] = $ex_hours;
				$data['sitting_entry'] = $sitting_entry;
			}
		} else {
			$data['success'] = true;
		}
		return Response::json($data, 200, []);
	}

	public function calCheck(Request $request){
		
		$check_in = $request->check_in;
		$hours_occ = $request->hours_occ;

		$ss_time = strtotime(date("h:i A",strtotime($check_in)));

		$new_time = date("h:i A", strtotime('+'.$hours_occ.' hours', $ss_time));

		$data['success'] = true;
		$data['check_out'] = $new_time;
		return Response::json($data, 200, []);
	}

	public function store(Request $request){
		// dd($request->balance_amount);

		$check_shift = Sitting::checkShift();

		$cre = [
			'name'=>$request->name,
		];

		$rules = [
			'name'=>'required',
		];

		$validator = Validator::make($cre,$rules);
		$date = Entry::getPDate();

		if($validator->passes()){
			$total_amount = $request->total_amount;
			if($request->id){
				$group_id = $request->id;
				$old_entry = Sitting::find($request->id);
				if($request->balance_amount <= 0){
					$data['success'] = false;
					$data['message'] = "Balance amount should be greater than 0";
					return Response::json($data, 200, []);
				}
				if($request->hours_occ <= $old_entry->hours_occ){
					$data['success'] = false;
					$data['message'] = "hours should be greater ".$old_entry->hours_occ." hours";
					return Response::json($data, 200, []);
				}
				$message = "Updated Successfully!";
				
				$total_amount = $total_amount - $old_entry->paid_amount;
				$entry = new Sitting;
				$message = "Stored Successfully!";
				$entry->unique_id = strtotime('now');
				$entry->check_in = $old_entry->check_out;
				
				$entry->hours_occ = $request->hours_occ - $old_entry->hours_occ;
				$entry->added_by = Auth::id();
				$entry->date = $date;
				$entry->created_at = date('Y-m-d H:i:s');
				$entry->checkout_status = $request->checkout_process ? 1 : 0;
				$entry->save();
				$entry->slip_id = Auth::user()->client_id.$entry->id;


				$old_entry->checkout_status = 1;
				$old_entry->checkout_time = date("Y-m-d H:i:s");
				$old_entry->checkout_by = Auth::id();
				$old_entry->save();


			} else {
				$entry = new Sitting;
				$message = "Stored Successfully!";
				$entry->unique_id = strtotime('now');
				$entry->check_in = date("H:i:s");
				$entry->hours_occ = $request->hours_occ ? $request->hours_occ : 0;
				$entry->added_by = Auth::id();
				$entry->date = $date;
				$entry->created_at = date('Y-m-d H:i:s');
			}

			$entry->name = $request->name;
			$entry->pnr_uid = $request->pnr_uid;
			$entry->mobile_no = $request->mobile_no;
			
			$entry->no_of_adults = $request->no_of_adults ? $request->no_of_adults : 0;
			$entry->no_of_children = $request->no_of_children ? $request->no_of_children : 0;
			$entry->no_of_baby_staff = $request->no_of_baby_staff ? $request->no_of_baby_staff : 0;
			$entry->seat_no = $request->seat_no;
			$entry->paid_amount = $total_amount;
			$entry->pay_type = $request->pay_type;
			$entry->remarks = $request->remarks;
			$entry->shift = $check_shift;
			$entry->client_id = Auth::user()->client_id;
			$entry->save();
			$entry->slip_id = Auth::user()->client_id.$entry->id;
			$no_of_min = $entry->hours_occ*60;
			$entry->check_out = date("H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->check_in)));
			$entry->checkout_date = date("Y-m-d H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->check_in)));
			$entry->save();

			$data['id'] = $entry->id;
			$data['success'] = true;

		} else {
			$data['success'] = false;
			$message = $validator->errors()->first();
		}

		return Response::json($data, 200, []);

	}

	public function printReports(){
		$print_data = new \stdClass;
		$data = Sitting::totalShiftData();
		$print_data->type = "shift";
		$print_data->total_shift_cash = $data['total_shift_cash']; 
		$print_data->total_shift_upi = $data['total_shift_upi'];
		$print_data->total_collection = $data['total_collection'];
		$print_data->last_hour_upi_total = $data['last_hour_upi_total'];
		$print_data->last_hour_cash_total = $data['last_hour_cash_total'];
		$print_data->last_hour_total = $data['last_hour_total'];
		$print_data->check_shift = $data['check_shift'];
		$print_data->shift_date = $data['shift_date'];
		$this->printFinal($print_data);
	}
	
	public function printPost($id = 0){

        $print_data = DB::table('sitting_entries')->where('id', $id)->first();
		$print_data->type = "silip";
        
        $print_data->total_member = $print_data->no_of_adults + $print_data->no_of_children + $print_data->no_of_baby_staff;
        $print_data->adult_amount = 0;
        $print_data->children_amount = 0;
        $hours = $print_data->hours_occ;
        $rate_list = DB::table("sitting_rate_list")->where("client_id", Auth::user()->client_id)->first();

        if($hours > 0) {
            $print_data->adult_amount = $print_data->no_of_adults * $hours * $rate_list->adult_rate;
            $print_data->children_amount = $print_data->no_of_children * $rate_list->child_rate * $hours;
        }
              
		return view('admin.sitting.print_sitting',compact('print_data'));
	}

    public function delete($id){
    	DB::table('sitting_entries')->where('client_id', Auth::user()->client_id)->where('id',$id)->update([
    		'deleted' => 1,
    		'delete_by' => Auth::id(),
    		'delete_time' => date("Y-m-d H:i:s"),

    	]);

    	$data['success'] = true;
    	$data['message'] = "Successfully";
		
		return Response::json($data, 200, []);
    }


}
