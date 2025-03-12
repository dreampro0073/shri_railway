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

		$entries = $entries->take(50);
		$entries = $entries->get();


		foreach ($entries as $key => $entry) {
			$entry->incoming_type = (isset($entry->incoming_type_id))?$show_incoming_types[$entry->incoming_type_id]:'NA';
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
		];

		$rules = [
			'name'=>'required',
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
		$print_data = DB::table('scanning_entries')->select('id')->where('barcodevalue', $print_id)->where("client_id", Auth::user()->client_id)->first();

		$print_url = url('view-scanning/'.$print_data->id);

		return view("admin.scanning_entries.print_bill",[
			'print_url'=>$print_url
		]);

	}

	public function viewScanning(Request $request,$print_id=0){
		$print_data = DB::table('scanning_entries')->select('scanning_entries.name','scanning_entries.no_of_item','scanning_entries.item_type_id','scanning_entries.incoming_type_id','scanning_item_types.item_type_name','scanning_entries.date','scanning_entries.slip_id')->leftJoin('scanning_item_types','scanning_item_types.id','=','scanning_entries.item_type_id')->where('scanning_entries.id',$print_id)->first();

		return view("admin.scanning_entries.view_details",[
			'print_data'=>$print_data
		]);
	}

}


