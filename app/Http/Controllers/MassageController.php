<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Massage, App\Models\User;
use App\Models\Entry;

class MassageController extends Controller {
	
	public function massage(){
		return view('admin.massage.index', [
            "sidebar" => "massage",
            "subsidebar" => "massage",
        ]);
	}

	public function initMassage(Request $request){
		$m_entries = DB::table('massage_entries')->select('massage_entries.*');
		if($request->unique_id){
			$m_entries = $m_entries->where('massage_entries.unique_id', 'LIKE', '%'.$request->unique_id.'%');
		}	

		$m_entries = $m_entries->orderBy('id','DESC');
		if(Auth::user()->priv != 1){
		}
		$m_entries = $m_entries->where('client_id',Auth::user()->client_id)->take(200);

		$m_entries = $m_entries->get();
		$show_pay_types = Entry::showPayTypes();
		$pay_types = Entry::payTypes();

		$data['success'] = true;
		$data['m_entries'] = $m_entries;
		$data['pay_types'] = $pay_types;

		$rate_list = Massage::rateList();
		$data['rate_list'] =$rate_list;

		return Response::json($data,200,array());
	}
	public function editMassage(Request $request){
		$m_entry = Massage::where('id', $request->m_id)->first();
		if($m_entry){
			$m_entry->paid_amount = $m_entry->paid_amount*1;
		}

		$data['success'] = true;
		$data['m_entry'] = $m_entry;
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
			if($request->id){
				$group_id = $request->id;
				$entry = Massage::find($request->id);
				$message = "Updated Successfully!";
			} else {
				$entry = new Massage;
				$message = "Stored Successfully!";
				$entry->slip_id = Massage::getSlipId();
				$entry->unique_id = strtotime('now');
			}

			$entry->name = $request->name;
			$entry->paid_amount = $request->paid_amount;
			$entry->pay_type = $request->pay_type;
			$entry->remarks = $request->remarks;
			$entry->time_period = $request->time_period;
			$entry->shift = $check_shift;
			$entry->no_of_person = $request->has('no_of_person')?$request->no_of_person:0;
			$entry->added_by = Auth::id();
			$entry->client_id = Auth::user()->client_id;

			$date = Entry::getPDate();
	        $entry->date = $date;
			$entry->save();
	
			$data['id'] = $entry->id;
			$data['success'] = true;
		} else {
			$data['success'] = false;
			$message = $validator->errors()->first();
		}
		return Response::json($data, 200, []);
	}
	public function printPost($id = 0){
        $print_data = DB::table('massage_entries')->where('id', $id)->first();
        return view('admin.massage.print_massage', compact('print_data'));

	}
	public function delete($id){
    	DB::table('massage_entries')->where('id',$id)->update([
    		'deleted' => 1,
    		'deleted_by' => Auth::id(),
    		'delete_time' => date("Y-m-d H:i:s"),
    	]);
    	$data['success'] = true;
    	$data['message'] = "Successfully";
		
		return Response::json($data, 200, []);
    }
}
