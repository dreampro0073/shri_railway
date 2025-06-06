<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Entry, App\Models\User, App\Models\Rest;

class RestController extends Controller {
	public function index(Request $request){
		$service_ids = Session::get('service_ids');
		if(in_array(10, $service_ids)){
			return view('admin.rest_entries.index', [
	            "sidebar" => "rest",
	            "subsidebar" => "rest",
	        ]);
		} else {
			return view('error');
		}
	}
	public function init(Request $request){

		
		$entries = Rest::where("rest_entries.client_id", Auth::user()->client_id);
		if($request->slip_id){
			$entries = $entries->where('rest_entries.slip_id', $request->slip_id);
		}				
		
		
		$entries = $entries->orderBy('id', "DESC");
		$entries = $entries->take(100);
		$entries = $entries->get();

		$show_pay_types = Entry::showPayTypes();

		foreach ($entries as $key => $entry) {
			$entry->show_pay_type = (isset($entry->pay_type))?$show_pay_types[$entry->pay_type]:'NA';
			$entry->show_date_time = date("d-m-Y H:i a",strtotime($entry->date_time));
			$entry->show_valid = ($entry->checkout_date)?date("d-m-Y H:i a",strtotime($entry->checkout_date)):'';
		}
		
		$rate_list = Rest::rateList();
		$pay_types = Entry::payTypes();
		$hours = Entry::hours();
		$data['success'] = true;
		$data['entries'] = $entries;
		$data['pay_types'] = $pay_types;
		$data['rate_list'] = $rate_list;
		return Response::json($data, 200, []);
	}	

	

	public function store(Request $request){

		$date = Entry::getPDate();
		$user = Auth::user();
		$check_shift = Entry::checkShift();

		$cre = [
			'no_of_hours'=>$request->no_of_hours,
			'pay_type'=>$request->pay_type,
		];

		$rules = [
			'no_of_hours'=>'required',
			'pay_type'=>'required',
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){
			if($request->id){
				$entry = Rest::find($request->id);
			} else {
				$entry = new Rest;
				$message = "Stored Successfully!";
				
				
			}

			$entry->date = $date;
			$entry->added_by = Auth::id();
			$entry->paid_amount = $request->paid_amount;
			$entry->no_of_hours = $request->no_of_hours;
			$entry->no_of_people = $request->no_of_people;
			$entry->pay_type = $request->pay_type;
			$entry->slip_id = Rest::getSlipId();
			$entry->check_in = date("H:i:s");
			$entry->date_time = date("Y-m-d H:i:s");
			$entry->client_id = Auth::user()->client_id;
			$entry->save();
			$no_of_min = $entry->no_of_hours*60;

			$entry->checkout_date = date("Y-m-d H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->date_time)));
			$entry->save();


			$data['id'] = $entry->id;
			$data['print_id'] = $entry->id;

			$data['success'] = true;
		} else {
			$data['success'] = false;
			$message = $validator->errors()->first();
		}
		return Response::json($data, 200, []);
	}

	public function printBill($id = 0){

        $print_data = DB::table('rest_entries')->select("rest_entries.no_of_hours","rest_entries.no_of_people","rest_entries.slip_id","rest_entries.date_time","rest_entries.paid_amount","clients.gst","clients.address as client_address","rest_entries.pay_type",'rest_entries.checkout_date')->leftJoin('clients','clients.id','=','rest_entries.client_id')->where("rest_entries.client_id", Auth::user()->client_id)->where('rest_entries.id', $id)->first();

        // dd($print_data);

        $show_pay_types = Entry::showPayTypes();

        // dd($show_pay_types);

        if($print_data){
        	$print_data->show_pay_type = (isset($print_data->pay_type))?$show_pay_types[$print_data->pay_type]:'NA';
        	$print_data->check_in = (isset($print_data->date_time))?date("H:i a",strtotime($print_data->date_time)):'';
        	$print_data->check_out = (isset($print_data->checkout_date))?date("H:i a",strtotime($print_data->checkout_date)):'';
        	$print_data->date = (isset($print_data->date_time))?date("d-m-Y",strtotime($print_data->date_time)):'';
        	
			
        }

        // dd($print_data);

		   
		return view('admin.rest_entries.print_bill',compact('print_data'));
	}

}
