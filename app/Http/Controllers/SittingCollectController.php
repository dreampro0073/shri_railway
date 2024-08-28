<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Massage, App\Models\User;
use App\Models\Entry;
use App\Models\Sitting;
use App\Models\CollectedSitting;

class SittingCollectController extends Controller {	
	
	public function init(Request $request){

		$check_shift = Entry::checkShift();
        $date = Entry::getPDate();

		$entries = Sitting::select('sitting_entries.*')->where("sitting_entries.client_id", Auth::user()->client_id)->where('checkout_status',1)->where('is_collected',0)->where('pay_type',1)->where('hours_occ','>',1)->where("added_by", Auth::user()->perent_user_id)->where('date',$date)->where('is_checked',0)->where('is_late',0);
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
		
		
		$entries = $entries->orderBy("checkout_status", 'ASC')->orderBy('id', "DESC");
		$entries = $entries->take(50);
		$entries = $entries->get();
		foreach ($entries as $item) {
			$item->show_time = date("h:i A",strtotime($item->check_in)).' - '.date("h:i A",strtotime($item->check_out));
			$e_total = DB::table('e_entries')->where('entry_id',$item->id)->where('is_collected',0)->where('is_checked',0)->sum('paid_amount');

			$item->paid_amount = $item->paid_amount + $e_total;
		}
		$c_sum = DB::table("collected_sitting")->where('date', date("Y-m-d",strtotime($date)))->sum("collected_amount");
		

		$e_entries_list = DB::table("e_entries")->where("is_checked", "=", 0)->where("added_by", Auth::user()->parent_user_id)->where('type',2)->where("is_collected",0)->where('pay_type',1)->get();


		$e_ent_sum = DB::table("collected_e_entries")->where('date', date("Y-m-d",strtotime($date)))->where("shift", $check_shift)->where('pay_type',1)->sum("paid_amount");
		
		$checked = DB::table('check_status')->select('check_status.*','users.name')->leftJoin('users','users.id','=','check_status.checked_by')->orderBy('id','DESC')->first();

		$data['success'] = true;
		$data['entries'] = $entries;
		$data['e_entries_list'] = $e_entries_list;
		$data['c_sum'] = $c_sum;
		$data['e_ent_sum'] = $e_ent_sum;
		$data['checked'] = $checked;
		return Response::json($data, 200, []);
	}

	public function storeCollectSit(Request $request){
		
		CollectedSitting::sittingCollection($request);

		$data['success'] = true;
		$data['message'] = "Done";
		return Response::json($data, 200, []);

	}

	public function storePen(Request $request){
		$id = $request->id;

		CollectedSitting::penltyCollection($id);
		$data['success'] = true;
		$data['message'] = "Done";
		return Response::json($data, 200, []);

	}
    public function collectSitting(){
    	return view('admin.sitting.collect_sitting',["sidebar" => "csitting",
            "subsidebar" => "csitting"]);

    }

}
