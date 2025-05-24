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

class CloakRoomCollectController extends Controller {	
	
	public function initRoom(Request $request){

		$check_shift = Entry::checkShift();
        $date = Entry::getPDate();

		$l_entries = DB::table('cloakroom_entries')->select('cloakroom_entries.*','users.name as username')->leftJoin('users','users.id','=','cloakroom_entries.delete_by')->where("cloakroom_entries.client_id", Auth::user()->client_id);
		if($request->id){
			$l_entries = $l_entries->where('cloakroom_entries.id', $request->id);
		}

		if($request->unique_id){
			$l_entries = $l_entries->where('cloakroom_entries.unique_id', 'LIKE', '%'.$request->unique_id.'%');
		}		

		if($request->name){
			$l_entries = $l_entries->where('cloakroom_entries.name', 'LIKE', '%'.$request->name.'%');
		}		
		if($request->mobile_no){
			$l_entries = $l_entries->where('cloakroom_entries.mobile_no', 'LIKE', '%'.$request->mobile_no.'%');
		}		
		if($request->pnr_uid){
			$l_entries = $l_entries->where('cloakroom_entries.pnr_uid', 'LIKE', '%'.$request->pnr_uid.'%');
		}	

		$l_entries = $l_entries->where("added_by", Auth::user()->perent_user_id)->where('date', $date)->where("is_checked", '=', 0)->where("is_late", 0)->where("pay_type", 1)->where("no_of_bag", '>', 1)->where('no_of_day',1)->where('is_collected', '=', 0)->where("checkout_status", 1)->get();

		foreach ($l_entries as $key => $item) {
			$item->checkin_date_show = date("d M, h:i A",strtotime($item->checkin_date));
			$item->checkout_date_show = date("d M, h:i A",strtotime($item->checkout_date));
		}

		$penlty_list = DB::table("cloakroom_penalities")->where("is_checked", "=", 0)->where("added_by", Auth::user()->perent_user_id)->where('is_collected','=', 0)->where('date', date("Y-m-d",strtotime($date)))->where("pay_type", 1)->get();

		$penalty_sum = DB::table("collected_penalities")->where('date', date("Y-m-d",strtotime($date)))->where("shift", $check_shift)->sum("paid_amount");
		$c_sum = DB::table("collected_cloakroom")->where('date', date("Y-m-d",strtotime($date)))->sum("collected_amount");

		// if($penalty_sum > 200){
		// 	$penlty_list = [];
		// }

		// if($c_sum > 300){
		// 	$data['l_entries'] = $l_entries;
		// }

		$data['success'] = true;
		$data['l_entries'] = $l_entries;
		// $data['l_entries'] = [];
		$data['penlty_list'] = $penlty_list;
		$data['penalty_sum'] = $penalty_sum;
		$data['c_sum'] = $c_sum;
		return Response::json($data, 200, []);
	}

	public function cData(Request $request){
		if(Auth::user()->priv == 1)
			$date = date("Y-m-d");
			$penalty_sum = DB::table("collected_penalities")->where('date', date("Y-m-d",strtotime($date)))->sum("paid_amount");
			$c_sum = DB::table("collected_cloakroom")->where('date', date("Y-m-d",strtotime($date)))->sum("collected_amount");

			$t_sum = $penalty_sum+$c_sum;

			$a_penalty_sum = DB::table("collected_penalities")->sum("paid_amount");
			$a_c_sum = DB::table("collected_cloakroom")->sum("collected_amount");

			$a_t_sum = $a_penalty_sum+$a_c_sum;

			echo "P Sum ".$penalty_sum." C Sum".$c_sum." T Sum".$t_sum."<br>"; 
			echo "All P Sum ".$a_penalty_sum."All C Sum".$a_c_sum."All T Sum".$a_t_sum."<br>"; 
		}else{

		}
	}

	public function storeCollectCloak(Request $request){
		$id = $request->id;
		$no_of_bag = $request->no_of_bag;

		CollectedPenalities::cloakroomCollection($id,$no_of_bag);

		$data['success'] = true;
		$data['message'] = "Done";
		return Response::json($data, 200, []);

	}

	public function storePen(Request $request){
		$id = $request->id;
		CollectedPenalities::penltyCollection($id);
		$data['success'] = true;
		$data['message'] = "Done";
		return Response::json($data, 200, []);

	}
    public function collectCloak(){
    	return view('admin.cloakrooms.collect_cloak',["sidebar" => "cloak-collect",
            "subsidebar" => "cloak-collect"]);

    }

}
