<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User;
use App\Models\Entry;
use App\Models\CloakRoom, App\Models\Sitting, App\Models\Canteen, App\Models\Massage, App\Models\Locker;


class ShiftController extends Controller {
	
	public function index(){
		return view('admin.shift.index', [
            "sidebar" => "shift",
            "subsidebar" => "shift",
        ]);
		
	}

	public function init(Request $request){

		$input_date = $request->input_date;
		$user_id = $request->has('user_id')?$request->user_id:0;
		
		$client_id = Auth::user()->client_id;
		
		if($request->client_id){
			$client_id = $request->client_id;
			$service_ids = DB::table("client_services")->where('status',1)->where("client_id", $client_id)->pluck('services_id')->toArray();
		} else {
			$service_ids = Session::get('service_ids');
		}

		$users = DB::table('users')->select('id','name')->where('priv','!=',4)->where("client_id", $client_id)->get();
		$clients = DB::table('clients')->where('org_id', Auth::user()->org_id)->pluck("client_name", 'id')->toArray();


		$current_shift = Entry::checkShift();
		$total_shift_upi = 0;
		$total_shift_cash = 0;
		$total_collection = 0;
		$last_hour_upi_total = 0;
		$last_hour_cash_total = 0;
		$last_hour_total = 0;

		if(Auth::user()->priv != 2){
            $user_id = Auth::id();
        }

		if(in_array(1, $service_ids)){
			$sitting_data = Sitting::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $sitting_data['total_shift_upi'];
			$total_shift_cash += $sitting_data['total_shift_cash'];
			$total_collection += $sitting_data['total_collection'];
			$last_hour_upi_total += $sitting_data['last_hour_upi_total'];
			$last_hour_cash_total += $sitting_data['last_hour_cash_total'];
			$last_hour_total += $sitting_data['last_hour_total'];
			$data['sitting_data'] = $sitting_data;

			if($user_id && Auth::user()->priv == 2){
				$data['chage_pay_type_data'] = Sitting::getChangePayTypeLog($input_date, $user_id);
			}

		}

		if(in_array(2, $service_ids)){
			$cloak_data = CloakRoom::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $cloak_data['total_shift_upi'];
			$total_shift_cash += $cloak_data['total_shift_cash'];
			$total_collection += $cloak_data['total_collection'];
			$last_hour_upi_total += $cloak_data['last_hour_upi_total'];
			$last_hour_cash_total += $cloak_data['last_hour_cash_total'];
			$last_hour_total += $cloak_data['last_hour_total'];
			$data['cloak_data'] = $cloak_data;
		}
		
		if(in_array(3, $service_ids)){
			$canteen_data = Canteen::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $canteen_data['total_shift_upi'];
			$total_shift_cash += $canteen_data['total_shift_cash'];
			$total_collection += $canteen_data['total_collection'];
			$last_hour_upi_total += $canteen_data['last_hour_upi_total'];
			$last_hour_cash_total += $canteen_data['last_hour_cash_total'];
			$last_hour_total += $canteen_data['last_hour_total'];
			$data['canteen_data'] = $canteen_data;
		}		

		if(in_array(4, $service_ids)){
			$massage_data = Massage::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $massage_data['total_shift_upi'];
			$total_shift_cash += $massage_data['total_shift_cash'];
			$total_collection += $massage_data['total_collection'];
			$last_hour_upi_total += $massage_data['last_hour_upi_total'];
			$last_hour_cash_total += $massage_data['last_hour_cash_total'];
			$last_hour_total += $massage_data['last_hour_total'];
			$data['massage_data'] = $massage_data;
		}		

		if(in_array(5, $service_ids)){
			$locker_data = Locker::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $locker_data['total_shift_upi'];
			$total_shift_cash += $locker_data['total_shift_cash'];
			$total_collection += $locker_data['total_collection'];
			$last_hour_upi_total += $locker_data['last_hour_upi_total'];
			$last_hour_cash_total += $locker_data['last_hour_cash_total'];
			$last_hour_total += $locker_data['last_hour_total'];
			$data['locker_data'] = $locker_data;
		}
	
        $data['total_shift_upi'] = $total_shift_upi;
		$data['total_shift_cash'] = $total_shift_cash;
		$data['total_collection'] = $total_collection;
		$data['last_hour_upi_total'] = $last_hour_upi_total;
		$data['last_hour_cash_total'] = $last_hour_cash_total;
		$data['last_hour_total'] = $last_hour_total;

		$data['success'] = true;
		$data['users'] = $users;
		$data['service_ids'] = $service_ids;
		$data['clients'] = $clients;
		return Response::json($data, 200, []);
	}
	
	public function print($type =1){
		$service_ids = Session::get('service_ids');
		$current_shift = Entry::checkShift();
		$total_shift_upi = 0;
		$total_shift_cash = 0;
		$total_collection = 0;
		$last_hour_upi_total = 0;
		$last_hour_cash_total = 0;
		$last_hour_total = 0;
		$sitting_data = [];
		$cloak_data = [];
		$canteen_data = [];
		$massage_data = [];
		$locker_data = [];

		$client_id = Auth::user()->client_id;
       
		if(Auth::user()->priv != 2){
            $user_id = Auth::id();
        }

        if(Auth::user()->priv ==4 && Auth::id() == 23){
        	$user_id = 19;
        }
        $input_date = date("Y-m-d");

		if(in_array(1, $service_ids)){
			$sitting_data = Sitting::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $sitting_data['total_shift_upi'];
			$total_shift_cash += $sitting_data['total_shift_cash'];
			$total_collection += $sitting_data['total_collection'];
			
		}

		if(in_array(2, $service_ids)){
			$cloak_data = CloakRoom::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $cloak_data['total_shift_upi'];
			$total_shift_cash += $cloak_data['total_shift_cash'];
			$total_collection += $cloak_data['total_collection'];
			
		}
		
		if(in_array(3, $service_ids)){
			$canteen_data = Canteen::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $canteen_data['total_shift_upi'];
			$total_shift_cash += $canteen_data['total_shift_cash'];
			$total_collection += $canteen_data['total_collection'];
			
		}		

		if(in_array(4, $service_ids)){
			$massage_data = Massage::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $massage_data['total_shift_upi'];
			$total_shift_cash += $massage_data['total_shift_cash'];
			$total_collection += $massage_data['total_collection'];
			
		}		

		if(in_array(5, $service_ids)){
			$locker_data = Locker::totalShiftData($input_date,$user_id,$client_id);
			$total_shift_upi += $locker_data['total_shift_upi'];
			$total_shift_cash += $locker_data['total_shift_cash'];
			$total_collection += $locker_data['total_collection'];
		}
		

        return view('admin.print_shift',[
        	'total_shift_upi'=>$total_shift_upi,
        	'total_shift_cash'=>$total_shift_cash,
        	'total_collection'=>$total_collection,
        	'sitting_data'=>$sitting_data,
			'cloak_data'=>$cloak_data,
			'canteen_data'=>$canteen_data,
			'massage_data'=>$massage_data,
			'locker_data'=>$locker_data,
        ]);
	}

}
