<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\ScanningEntry,App\Models\Entry;

class ScanningController extends Controller {

	public function index(Request $request){
		$sidebar = 'scanning';
        $subsidebar = 'scanning';

        return view('admin.scanning_entries.index',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function init(Request $request){

    	$show_incoming_types = ScanningEntry::showIncomingTypes();

		$entries = ScanningEntry::select('scanning_entries.*','scanning_item_types.item_type_name')->leftJoin('scanning_item_types','scanning_item_types.id','scanning_entries.item_type_id')->where("scanning_entries.client_id", Auth::user()->client_id);
		if($request->slip_id){
			$entries = $entries->where('scanning_entries.slip_id', $request->slip_id);
		}		
		if($request->name){
			$entries = $entries->where('scanning_entries.name', 'LIKE', '%'.$request->name.'%');
		}		

		$entries = $entries->take(500);
		$entries = $entries->orderBy('id','DESC')->get();

		$show_pay_types = Entry::showPayTypes()''


		foreach ($entries as $key => $entry) {
			$entry->incoming_type = (isset($entry->incoming_type_id))?$show_incoming_types[$entry->incoming_type_id]:'NA';
			$entry->show_pay_type = (isset($entry->pay_type))?$show_pay_types[$entry->pay_type]:'NA';
		}

		$rate_list = ScanningEntry::rateList();
		$pay_types = Entry::payTypes();
		$incoming_types = ScanningEntry::showIncomingTypes();
		$item_types = ScanningEntry::itemTypes();

		$data['success'] = true;
		$data['entries'] = $entries;
		$data['incoming_types'] = $incoming_types;
		$data['item_types'] = $item_types;
		$data['rate_list'] = $rate_list;
		return Response::json($data, 200, []);
	}	

	public function store(Request $request){

		$date = Entry::getPDate();
		$user = Auth::user();

		$check_shift = Entry::checkShift();

		$cre = [
			'name'=>$request->name,
			'no_of_item'=>$request->no_of_item,
			// 'paid_amount'=>$request->paid_amount,
			'item_type_id'=>$request->item_type_id,
			'incoming_type_id'=>$request->incoming_type_id,
			'pay_type'=>$request->pay_type,
		];

		$rules = [
			'name'=>'required',
			'no_of_item'=>'required',
			// 'paid_amount'=>'required',
			'item_type_id'=>'required',
			'incoming_type_id'=>'required',
			'pay_type'=>'required',
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){
			if($request->id){
				$entry = ScanningEntry::find($request->id);
				$message = "Updated Successfully!";
			} else {

				$entry = new ScanningEntry;
				$entry->slip_id = ScanningEntry::getSlipId();
				$message = "Stored Successfully!";
			}

			$entry->name = $request->name;
			$entry->unique_id = strtotime('now');
			$entry->no_of_item = $request->no_of_item;
			$entry->item_type_id = $request->item_type_id;
			$entry->incoming_type_id = $request->incoming_type_id;
			$entry->pay_type = $request->pay_type;
			$entry->date_time = date("Y-m-d H:i:s");
			$entry->date = date("Y-m-d");
			$entry->created_at = date("Y-m-d H:i:s");
			$entry->added_by = Auth::id();
			$entry->paid_amount = $request->paid_amount;
			$entry->client_id = Auth::user()->client_id;
			$entry->save();

			$barcodevalue = bin2hex($entry->unique_id);
			$entry->barcodevalue = $barcodevalue;
			$entry->save();

			$data['id'] = $entry->id;
			$data['print_id'] = $entry->barcodevalue;
			$data['success'] = true;

			$data['id'] = $entry->id;
			$data['print_id'] = $entry->barcodevalue;
			$data['success'] = true;
		} else {
			$data['success'] = false;
			$message = $validator->errors()->first();
		}
		return Response::json($data, 200, []);
	}

	public function printBill(Request $request,$print_id=0){
		// $print_data = DB::table('scanning_entries')->select('unique_id','id','no_of_item')->where('barcodevalue', $print_id)->where("client_id", Auth::user()->client_id)->first();

		$print_data = DB::table('scanning_entries')->select('scanning_entries.name','scanning_entries.id','scanning_entries.no_of_item','scanning_entries.item_type_id','scanning_entries.incoming_type_id','scanning_item_types.item_type_name','scanning_entries.date','scanning_entries.slip_id','scanning_entries.name as client_name','clients.gst','clients.address as client_address','scanning_entries.barcodevalue','scanning_entries.unique_id','scanning_entries.paid_amount','scanning_entries.date_time','scanning_entries.incoming_type_id','scanning_entries.print_count','scanning_entries.max_print','scanning_entries.pay_type','scanning_entries.date_time')->leftJoin('scanning_item_types','scanning_item_types.id','=','scanning_entries.item_type_id')->leftJoin('clients','clients.id','=','scanning_entries.client_id')->where('scanning_entries.barcodevalue',$print_id)->where("scanning_entries.client_id", Auth::user()->client_id)->first();

		if(Auth::user()->priv == 3 && $print_data->print_count >= $print_data->max_print){
			return "Print not allowed";
		}

		$print_data->incoming_type = "NA";
    	$show_incoming_types = ScanningEntry::showIncomingTypes();

    	$show_pay_types  = Entry::showPayTypes();

		if($print_data){
			$print_data->incoming_type = (isset($print_data->incoming_type_id))?$show_incoming_types[$print_data->incoming_type_id]:'NA';
			$print_data->show_pay_type = (isset($print_data->pay_type))?$show_pay_types[$print_data->pay_type]:'NA';

		}

		$print_data->print_url = url('view-scanning/'.$print_data->id);

		if(Auth::user()->priv == 3){
			DB::table('scanning_entries')->where('id',$print_data->id)->update([
	        	'print_count' => $print_data->print_count+1,
	        ]);	
		}

		return view("admin.scanning_entries.print_bill",[
			'print_data'=>$print_data
		]);

	}

	public function printQR(Request $request,$print_id=0){
		// $print_data = DB::table('scanning_entries')->select('unique_id','id','no_of_item')->where('barcodevalue', $print_id)->where("client_id", Auth::user()->client_id)->first();

		$print_data = DB::table('scanning_entries')->select('id','qr_print_count','max_qr_count','no_of_item')->where('scanning_entries.barcodevalue',$print_id)->where("scanning_entries.client_id", Auth::user()->client_id)->first();

		if(Auth::user()->priv == 3 && $print_data->qr_print_count >= $print_data->max_qr_count){
			return "Print not allowed";
		}

		$print_data->print_url = url('view-scanning/'.$print_data->id);

		if(Auth::user()->priv == 3){
			DB::table('scanning_entries')->where('id',$print_data->id)->update([
	        	'qr_print_count' => $print_data->qr_print_count+1,
	        ]);	
		}

		return view("admin.scanning_entries.print_qrcode",[
			'print_data'=>$print_data
		]);

	}

	public function viewScanning(Request $request,$print_id=0){
		$print_data = DB::table('scanning_entries')->select('scanning_entries.name','scanning_entries.no_of_item','scanning_entries.item_type_id','scanning_entries.incoming_type_id','scanning_item_types.item_type_name','scanning_entries.date','scanning_entries.slip_id','scanning_entries.name as client_name','clients.gst','clients.address as client_address','scanning_entries.paid_amount','scanning_entries.no_of_item')->leftJoin('scanning_item_types','scanning_item_types.id','=','scanning_entries.item_type_id')->leftJoin('clients','clients.id','=','scanning_entries.client_id')->where('scanning_entries.id',$print_id)->first();

		$print_data->incoming_type = "NA";
    	$show_incoming_types = ScanningEntry::showIncomingTypes();

		if($print_data){
			$print_data->incoming_type = (isset($print_data->incoming_type_id))?$show_incoming_types[$print_data->incoming_type_id]:'NA';

		}

		return view("admin.scanning_entries.view_details",[
			'print_data'=>$print_data
		]);
	}

}


