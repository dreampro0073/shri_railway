<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\User;
use App\Models\Entry;
use App\Models\CloakRoom, App\Models\Sitting, App\Models\Canteen, App\Models\Massage, App\Models\Locker,App\Models\Recliner,App\Models\Room,App\Models\ScanningEntry,App\Models\RestRoom;
use App\Models\Shift;
use App\Models\CollectedPenalities;





class ShiftController extends Controller {

	
	public function index(){

		return view('admin.shift.index', [
            "sidebar" => "shift",
            "subsidebar" => "shift",
        ]);
	}

	public function init(Request $request){
		$client_id = isset($request->client_id) ? $request->client_id : Auth::user()->client_id;
		
		if(Auth::user()->priv == 2){
			CollectedPenalities::setCheckStatus();
		}

		$service_ids = Entry::getServiceIds($client_id);
		$data = $this->getStatus($request->all(), $client_id, $service_ids);
		// $data = Shift::getStatus($request->all(), $client_id, $service_ids);
		$data['success'] = true;
		$data['users'] = DB::table('users')->select('id','name')->where('priv','!=',4)->where("client_id", $client_id)->get();
		$data['service_ids'] = $service_ids;
		$data['clients'] = Sitting::getBranches();
		return Response::json($data, 200, []);
	}
	
	public function print(Request $request, $type = 1){
		$client_id = isset($request->client_id) ? $request->client_id : Auth::user()->client_id;
		$service_ids = Entry::getServiceIds($client_id);
		$data = $this->getStatus($request->all(), $client_id, $service_ids);
		// $data = Shift::getStatus($request->all(), $client_id, $service_ids);
		$current_shift = Entry::checkShift();
		
        return view('admin.print_shift',[
        	'total_shift_upi'=> isset($data['total_shift_upi']) ? $data['total_shift_upi'] : 0,
        	'total_shift_cash'=> isset($data['total_shift_cash']) ? $data['total_shift_cash'] : 0,
        	'total_collection'=> isset($data['total_collection']) ? $data['total_collection'] : 0,
        	'sitting_data'=> isset($data['sitting_data']) ? $data['sitting_data'] : [],
			'cloak_data'=> isset($data['cloak_data']) ? $data['cloak_data'] : [],
			'canteen_data'=> isset($data['canteen_data']) ? $data['canteen_data'] : [],
			'massage_data'=> isset($data['massage_data']) ? $data['massage_data'] : [],
			'locker_data'=> isset($data['locker_data']) ? $data['locker_data'] : [],
			'recliner_data'=> isset($data['recliner_data']) ? $data['recliner_data'] : [],
			'pod_data'=> isset($data['pod_data']) ? $data['pod_data'] : [],
			'singal_cabin_data'=> isset($data['singal_cabin_data']) ? $data['singal_cabin_data'] : [],
			'double_bed_data'=> isset($data['double_bed_data']) ? $data['double_bed_data'] : [], 
			'scanning_data'=> isset($data['scanning_data']) ? $data['scanning_data'] : [], 
			'restroom_data'=> isset($data['restroom_data']) ? $data['restroom_data'] : [], 
			'service_ids'=>$service_ids,
        ]);
	}

	public function getStatus($request, $client_id, $service_ids){ 
		$input_date = isset($request['input_date']) ? $request['input_date'] : date("Y-m-d");
		if(Auth::user()->priv != 2){
            $user_id = Auth::id();
        } else{
			$user_id = isset($request['user_id']) ? $request['user_id'] : 0;
		}
		 
		$current_shift = Entry::checkShift();

		$data['total_shift_upi'] = 0;
		$data['total_shift_cash'] = 0;
		$data['total_collection'] = 0;
		$data['last_hour_upi_total'] = 0;
		$data['last_hour_cash_total'] = 0;
		$data['last_hour_total'] = 0;

		if(in_array(1, $service_ids)){
			$sitting_data = Sitting::totalShiftData($input_date,$user_id,$client_id);
			$data['sitting_data'] = $sitting_data;
			$data = $this->calculateAmount($sitting_data, $data);
			if($user_id && Auth::user()->priv == 2){
				$data['chage_pay_type_data'] = Sitting::getChangePayTypeLog($input_date, $user_id);
			}
		}

		if(in_array(2, $service_ids)){
			$cloak_data = CloakRoom::totalShiftData($input_date,$user_id,$client_id);
			$data['cloak_data'] = $cloak_data;
			$data = $this->calculateAmount($cloak_data, $data);
		}
		
		if(in_array(3, $service_ids)){
			$canteen_data = Canteen::totalShiftData($input_date,$user_id,$client_id);
			$data['canteen_data'] = $canteen_data;
			$data = $this->calculateAmount($canteen_data, $data);
		}		

		if(in_array(4, $service_ids)){
			$massage_data = Massage::totalShiftData($input_date,$user_id,$client_id);
			$data['massage_data'] = $massage_data;
			$data = $this->calculateAmount($massage_data, $data);
		}		

		if(in_array(5, $service_ids)){
			$locker_data = Locker::totalShiftData($input_date,$user_id,$client_id);
			$data['locker_data'] = $locker_data;
			$data = $this->calculateAmount($locker_data, $data);
		}

		if(in_array(7, $service_ids)){
			$recliner_data = Recliner::totalShiftData($input_date,$user_id,$client_id);
			$data['recliner_data'] = $recliner_data;
			$data = $this->calculateAmount($recliner_data, $data);
		}		

		if(in_array(8, $service_ids)){
			$pod_data = Room::totalShiftData(1,$input_date,$user_id,$client_id);
			$data['pod_data'] = $pod_data;
			$data = $this->calculateAmount($pod_data, $data);

			$singal_cabin_data = Room::totalShiftData(2,$input_date,$user_id,$client_id);
			$data['singal_cabin_data'] = $singal_cabin_data;
			$data = $this->calculateAmount($singal_cabin_data, $data);	

			$double_bed_data = Room::totalShiftData(3,$input_date,$user_id,$client_id);
			$data['double_bed_data'] = $double_bed_data;
			$data = $this->calculateAmount($double_bed_data, $data);
		}

		if(in_array(9, $service_ids)){
			$scanning_data = ScanningEntry::totalShiftData($input_date,$user_id,$client_id);
			$data['scanning_data'] = $scanning_data;
			$data = $this->calculateAmount($scanning_data, $data);
		}
		
		return $data;
	}

	public function calculateAmount($total_data, $data){

		$data['total_shift_upi'] += $total_data['total_shift_upi'];
		$data['total_shift_cash'] += $total_data['total_shift_cash'];
		$data['total_collection'] += $total_data['total_collection'];
		$data['last_hour_upi_total'] += $total_data['last_hour_upi_total'];
		$data['last_hour_cash_total'] += $total_data['last_hour_cash_total'];
		$data['last_hour_total'] += $total_data['last_hour_total'];
		return $data;

	}


}
