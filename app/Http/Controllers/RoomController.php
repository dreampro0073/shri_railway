<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User;
use App\Models\Room,App\Models\Room1;
use App\Models\Entry;

class RoomController extends Controller {	
	public function index($type){
		$sidebar = 'pods';
        $subsidebar = 'pods';

		if($type == 2){
			$sidebar = 'scabins';
           	$subsidebar = 'scabins';
		}

		if($type == 3){
			$sidebar = 'beds';
           	$subsidebar = 'beds';
		}

		return view('admin.rooms.index', [
            "sidebar" =>$sidebar,
            "subsidebar" => $subsidebar,
            "type" => $type,
        ]);
	}
	public function allEntries(){
		$sidebar = 'all-entries';
        $subsidebar = 'all-entries';

		return view('admin.rooms.all_entries', [
            "sidebar" =>$sidebar,
            "subsidebar" => $subsidebar,
        ]);
	}
	
	public function initEntry(Request $request,$type){
		$client_id = Auth::user()->client_id;
	
		$entries = DB::table('room_entries')->select('room_entries.*');

		if($request->unique_id){
			$entries = $entries->where('room_entries.unique_id', 'LIKE', '%'.$request->unique_id.'%');
		}		

		if($request->name){
			$entries = $entries->where('room_entries.name', 'LIKE', '%'.$request->name.'%');
		}		
		if($request->mobile_no){
			$entries = $entries->where('room_entries.mobile_no', 'LIKE', '%'.$request->mobile_no.'%');
		}		
		if($request->pnr_uid){
			$entries = $entries->where('room_entries.pnr_uid', 'LIKE', '%'.$request->pnr_uid.'%');
		}		
		
		$entries = $entries->where('checkout_status',0)->where('client_id',$client_id)->where('type',$type);
		$entries = $entries->orderBy('id', "DESC")->get();

		foreach ($entries as $key => $item) {
			$bm_amount = DB::table('room_e_entries')->where('status',0)->where('entry_id','=',$item->id)->sum('paid_amount');

			$item->sh_paid_amount = $item->paid_amount + $bm_amount;
			$item->check_in = date("d M y",strtotime($item->date))." ".date("h:i A",strtotime($item->check_in));
			$item->checkout_date = date("d M y, h:i A",strtotime($item->checkout_date));
			$item->show_e_ids = Room::getEnos($item->type,$item->e_ids);
			$item->str_checkout_time = strtotime($item->checkout_date);

		}

		$pay_types = Entry::payTypes();
		$hours = Room::hours();
		$show_pay_types = Entry::showPayTypes();
		$avail_pods = Room::getAvailPods();
		$avail_cabins = Room::getAvailSinCabins();
		$avail_beds = Room::getAvailBeds();

		$data['success'] = true;
		$data['entries'] = $entries;
		$data['pay_types'] = $pay_types;
		$data['hours'] = $hours;
		$data['avail_pods'] = $avail_pods;
		$data['avail_cabins'] = $avail_cabins;
		$data['avail_beds'] = $avail_beds;

		return Response::json($data, 200, []);
	}
	public function initAllEntry(Request $request){
		$entries = DB::table('room_entries')->select('room_entries.*');
		if($request->unique_id){
			$entries = $entries->where('room_entries.unique_id', 'LIKE', '%'.$request->unique_id.'%');
		}		

		if($request->name){
			$entries = $entries->where('room_entries.name', 'LIKE', '%'.$request->name.'%');
		}		
		if($request->mobile_no){
			$entries = $entries->where('room_entries.mobile_no', 'LIKE', '%'.$request->mobile_no.'%');
		}		
		if($request->pnr_uid){
			$entries = $entries->where('room_entries.pnr_uid', 'LIKE', '%'.$request->pnr_uid.'%');
		}		
	
		$entries = $entries->where('client_id', Auth::user()->client_id)->orderBy('id', "DESC")->take(100)->get();

		foreach ($entries as $key => $item) {
			$bm_amount = DB::table('room_e_entries')->where('status',0)->where('entry_id','=',$item->id)->sum('paid_amount');
			$item->sh_paid_amount = $item->paid_amount + $bm_amount;
			$item->check_in = date("d M y",strtotime($item->date))." ".date("h:i A",strtotime($item->check_in));
			$item->checkout_date = date("d M y, h:i A",strtotime($item->checkout_date));

			$item->show_e_ids = Room::getEnos($item->type,$item->e_ids);
			$item->str_checkout_time = strtotime($item->checkout_date);
			
		}

		$data['success'] = true;
		$data['entries'] = $entries;

		return Response::json($data, 200, []);
	}

	public function initSingleEntry(Request $request){
		$l_entry = Entry::where('id', $request->entry_id)->first();
		if($l_entry){
			$room_e_entries = DB::table('room_e_entries')->select('room_e_entries.*','users.name as username')->leftJoin('users','users.id','=','room_e_entries.added_by')->where('room_e_entries.status',0)->where('room_e_entries.client_id','=',Auth::user()->client_id)->where('room_e_entries.entry_id','=',$l_entry->id)->get();

			$bm_amount = DB::table('room_e_entries')->where('status',0)->where('entry_id','=',$l_entry->id)->sum('paid_amount');
			$l_entry->sh_paid_amount = $l_entry->paid_amount + $bm_amount;

			$l_entry->show_e_ids = Room::getEnos($l_entry->type,$l_entry->e_ids);
			$l_entry->room_e_entries = $room_e_entries;

			$l_entry->add_date = date("d M y, h:i A",strtotime($l_entry->created_at));

			if($l_entry->is_late == 0){
				$l_entry->checkout_date = date("d M y, h:i A",strtotime($l_entry->checkout_date));

			}else{
				$l_entry->checkout_date = date("d M y, h:i A",strtotime($l_entry->checkout_time));

			}

		}

		$data['success'] = true;
		$data['l_entry'] = $l_entry;
		return Response::json($data, 200, []);
	}
	
	public function editEntry(Request $request){
		$l_entry = Room::where('id', $request->entry_id)->where('client_id','=',Auth::user()->client_id)->first();

		$sl_pods = [];
		$sl_cabins = [];
		$sl_beds = [];

		if($l_entry){
			$l_entry->mobile_no = $l_entry->mobile_no*1;
			$l_entry->train_no = $l_entry->train_no*1;
			$l_entry->pnr_uid = $l_entry->pnr_uid;
			$l_entry->paid_amount = $l_entry->paid_amount*1;
			$l_entry->total_amount = $l_entry->total_amount*1;
			$l_entry->discount_amount = $l_entry->discount_amount*1;

			$l_entry->check_in = date("h:i A",strtotime($l_entry->check_in));
			$l_entry->check_out =date("h:i A",strtotime($l_entry->check_out));

			$l_entry->show_e_ids = Room::getEnos($l_entry->type,$l_entry->e_ids);



			$e_ids =  explode(',', $l_entry->e_ids);
			
			if($l_entry->type == 1){
				$sl_pods = $e_ids;

			}else if($l_entry->type == 2){
				$sl_cabins = $e_ids;

			}else{
				$sl_beds = $e_ids;
			}

			$bm_amount = DB::table('room_e_entries')->where('client_id','=',Auth::user()->client_id)->where('status',0)->where('entry_id','=',$l_entry->id)->sum('paid_amount');
			$l_entry->paid_amount = $l_entry->paid_amount + $bm_amount;
			$l_entry->bm_amount = $bm_amount;
		}

		$data['success'] = true;
		$data['l_entry'] = $l_entry;
		$data['sl_pods'] = $sl_pods;
		$data['sl_cabins'] = $sl_cabins;
		$data['sl_beds'] = $sl_beds;
		return Response::json($data, 200, []);
	}
	public function calCheck(Request $request){
		
		$check_in = $request->check_in;
		$no_of_day = $request->no_of_day;

		$hours = 24*$no_of_day;
		$ss_time = strtotime(date("h:i A",strtotime($check_in)));
		$new_time = date("h:i A", strtotime('+'.$hours.' hours', $ss_time));

		$data['success'] = true;
		$data['check_out'] = $new_time;
		return Response::json($data, 200, []);
	}



	public function store(Request $request,$type){

		$user_session_id = Auth::user()->session_id;
		$user_id = Auth::id();
		$client_id = Auth::user()->client_id;

		$check_shift = Entry::checkShift();
		$date = Entry::getPDate();

		$cre = [
			'name'=>$request->name,
		];

		$rules = [
			'name'=>'required',
		];
		$validator = Validator::make($cre,$rules);
		if($validator->passes()){
			$total_amount = $request->total_amount;
			$paid_amount = $request->paid_amount;
			$balance_amount = $request->balance_amount;
			if($request->id){
				$entry = Room::find($request->id);
				$message = "Updated Successfully!";
				if($user_id != $entry->added_by){
					DB::table('room_e_entries')->insert([
						'entry_id' => $entry->id,
						'added_by' => $user_id,
						'client_id' => $client_id,
						'date' => $date,
						'pay_type' => $request->pay_type,
						'type' => $type,
						'shift' => $check_shift,
						'paid_amount' => $request->balance_amount,
						'created_at' => date("Y-m-d H:i:s"),
						'current_time' => date("H:i:s"),
					]);
				}else{
					$paid_amount = $paid_amount + $balance_amount;
					$entry->paid_amount = $paid_amount;
				}
			} else {
				$entry = new Room;
				$message = "Stored Successfully!";
				$entry->unique_id = strtotime('now');
				$entry->paid_amount = $paid_amount;
				$entry->added_by = $user_id;
				$entry->pay_type = $request->pay_type;
				$entry->created_at = date('Y-m-d H:i:s');
				$entry->date = $date;
				$entry->client_id = $client_id;

			}

			$entry->name = $request->name;
			$entry->pnr_uid = $request->pnr_uid;
			$entry->mobile_no = $request->mobile_no;
			
			$entry->hours_occ = $request->hours_occ ? $request->hours_occ : 0;

			if($request->id){
				$entry->check_in = date("H:i:s",strtotime($request->check_in));
			}else{
				$entry->check_in = date("H:i:s");
			}
			
			$entry->total_amount = $total_amount;
			$entry->discount_amount = $request->has('discount_amount')?$request->discount_amount:0;

			$entry->remarks = $request->remarks;
			$entry->shift = $check_shift;
			$entry->type = $type;
			$entry->save();
			$no_of_min = $request->hours_occ*60;
			$no_of_min = $no_of_min - 1;

			$entry->check_out = date("H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->check_in)));

	        
			$checkin_date = $date." ".$entry->check_in;
			$checkout_date = date("Y-m-d H:i:s",strtotime("+".$no_of_min.' minutes',strtotime($checkin_date)));

	        $entry->checkout_date = $checkout_date;
			if($type ==1){
				$sl_pods = $request->sl_pods;
				$entry->e_ids = implode(',', $sl_pods);
				DB::table("pods")->where('client_id','=',$client_id)->whereIn('id',$sl_pods)->update(['status'=>1]);
			}
			if($type == 2){
				$sl_cabins = $request->sl_cabins;
				$entry->e_ids = implode(',', $sl_cabins);
				DB::table("single_cabins")->where('client_id','=',$client_id)->whereIn('id',$sl_cabins)->update(['status'=>1]);
			}

			if($type == 3){
				$sl_beds = $request->sl_beds;
				$entry->e_ids = implode(',', $sl_beds);
				DB::table("double_beds")->where('client_id','=',$client_id)->whereIn('id',$sl_beds)->update(['status'=>1]);
			}
			$entry->status = 1;
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

        $print_data = DB::table('room_entries')->where('client_id','=',Auth::user()->client_id)->where('id', $id)->first();

        $total_amount = $print_data->paid_amount;
       
        $e_total = DB::table('room_e_entries')->select('paid_amount')->where('status',0)->where('entry_id', $print_data->id)->sum('paid_amount');

       	$total_amount =  ($print_data->total_amount + $e_total) - $print_data->discount_amount;


        if($print_data){
        	$print_data->show_e_ids = Room::getEnos($print_data->type,$print_data->e_ids);
        }
        return view('admin.rooms.print_page_entery', compact('print_data','total_amount'));
	}


    public function checkoutInit(Request $request){

    	$now_time = strtotime(date("Y-m-d H:i:s",strtotime("+10 minutes")));
    	$l_entry = Room::where('id', $request->entry_id)->first();
    	$checkout_time = strtotime($l_entry->checkout_date);

    	if($checkout_time > $now_time){
    		$data['timeOut'] = false;
    		$entry = Room::find($request->entry_id);
    		$entry->status = 1; 
    		$entry->checkout_status = 1; 
			$entry->checkout_time = date('Y-m-d H:i:s'); 

    		$entry->save();
    		$data['success'] = true;

			$e_ids = explode(',', $l_entry->e_ids);

			Room::updateAvailStatus($l_entry->type,$e_ids);    
    	} else {
    		$hour = round(($now_time - $checkout_time)/(60 * 60));

    		$e_ids = explode(',', $l_entry->e_ids);
    		$l_entry->show_e_ids = Room::getEnos($l_entry->type,$l_entry->e_ids);

			$l_entry->mobile_no = $l_entry->mobile_no*1;
			$l_entry->pnr_uid = $l_entry->pnr_uid;
			$l_entry->paid_amount = $l_entry->paid_amount*1;

			$l_entry->check_in = date("H:i A",strtotime($l_entry->check_in));
			$l_entry->check_out = date("H:i A",strtotime($l_entry->check_out));


			$balance = Room::getAmount($l_entry->type,$hour,sizeof($e_ids));
			// dd($balance);
			$l_entry->balance = $balance;
			$l_entry->collect_amount = $balance;
			$l_entry->total_balance = $l_entry->paid_amount+$l_entry->balance;
			$l_entry->hour = $hour;
			$data['l_entry'] = $l_entry;
			$data['success'] = true;
			$data['timeOut'] = true;
		}

		return Response::json($data, 200, []);
    }

    public function checkoutStore(Request $request){
    	$check_shift = Entry::checkShift();
    	$entry = Room::find($request->id);

		$entry->checkout_status = 1;
		$entry->penality = $request->collect_amount;
		$entry->checkout_time = date('Y-m-d H:i:s'); 
		$entry->is_late = 1;
		$entry->late_hr = $request->hour;
		$entry->save();

		$date = Entry::getPDate();
		DB::table('room_e_entries')->insert([
			'entry_id' => $entry->id,
			'paid_amount' => $request->collect_amount,
			'balance' => $request->balance,
			'pay_type' => $request->pay_type,
			'type' => $entry->type,
			'shift' => $check_shift,
			'date' =>$date,
			'added_by' =>Auth::id(),
			// 'user_session_id' => Auth::user()->session_id,
			'current_time' => date("H:i:s"),
			'created_at' => date('Y-m-d H:i:s'),
			'is_checkout' => 1,
		]);

		$e_ids = explode(',', $request->e_ids);
		Room::updateAvailStatus($entry->type,$e_ids);

		$data['success'] = true;
		$data['entry_id'] = $entry->id;
		
		return Response::json($data, 200, []);
    }

    public function checkoutWithoutPenalty($id){
		if(Auth::user()->priv == 2){
			$entry = Room::where("checkout_status", "!=", 1)->where('id',$id)->first();
			$entry->is_late = 1;
			$entry->checkout_status = 1;
			$entry->checkout_by = Auth::id();
			$entry->checkout_time = date("Y-m-d H:i:s");
			$entry->save();

			$e_ids = explode(',', $entry->e_ids);

			Room::updateAvailStatus($entry->type,$e_ids);   
		} 

		return Redirect::back();
	}
    
 
	public function deleteEnEntry($entry_id =0,$e_entry_id=0){
    	$check = DB::table('room_e_entries')->where('id',$e_entry_id)->where('entry_id',$entry_id)->update(['status'=>1]);

    	$data['success'] = true;
    	$data['message'] = "Successfully Deleted";

		return Response::json($data, 200, []);
	}
	public function availInit(Request $request){
		$hours = Room::hours();
		$types = Room::types();

		$data['success'] = true;
		$data['types'] = $types;
		$data['hours'] = $hours;

		return Response::json($data,200,[]);
	}
	public function getRoomAmount(Request $request){
		$type = $request->type;
		$hours_occ = $request->hours_occ;
		$no_of_rooms = $request->no_of_rooms;

		$total_amount = Room::getAmount($type,$hours_occ,$no_of_rooms);

		$booking_amount = 0;
		if($type == 1){
			$booking_amount = 100*$no_of_rooms;
		}else if($type == 2){
			$booking_amount = 200*$no_of_rooms;
		}else if($type == 3){
			$booking_amount = 300*$no_of_rooms;
		}


		$data['success'] = true;
		$data['total_amount'] = $total_amount;
		$data['booking_amount'] = $booking_amount;
		return Response::json($data,200,[]);
	}

	public function getCheckoutTime(Request $request){
		$check_in = date('H:i:s', strtotime($request->check_in));
		$no_of_min = ($request->hours_occ * 60) - 1;
		$checkout_time = date("h:i A",strtotime("+{$no_of_min} minutes", strtotime($check_in)));
		$data['checkout_time'] = $checkout_time;
		$data['success'] = true;


		return Response::json($data, 200, []);
	}

	public function bookRoom(Request $request){
		$entry = new Room1;
		$entry->name = $request->name;
		$entry->mobile_no = $request->mobile_no;
		$entry->email_id = $request->email_id;
		$entry->pnr_uid = $request->pnr_uid;
		$entry->hours_occ = $request->hours_occ;
		$entry->full_payment = $request->full_payment;
		$entry->date = date("Y-m-d",strtotime($request->date));
		$entry->type = $request->type;
		$entry->hours_occ = $request->hours_occ;
		$entry->no_of_rooms = $request->no_of_rooms;
		$entry->client_id = 1;
		$entry->added_by = User::systemCreatedId();
		$entry->online_booking = 1;
		$entry->save();

		if($entry->full_payment == 1){
			$entry->total_amount = $request->total_amount;
			$entry->paid_amount = $request->total_amount;
		}else{
			$entry->booking_amount = $request->booking_amount;
			$entry->paid_amount = $request->booking_amount;
			$entry->total_amount = $request->booking_amount;
		}
		$entry->check_in = date("H:i:s",strtotime($request->check_in));

		$entry->save();

		$no_of_min = $entry->hours_occ*60;
		$no_of_min = $no_of_min - 1;
		$entry->check_out = date("H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->check_in)));

		$checkin_date = $entry->date." ".$entry->check_in;
		$checkout_date = date("Y-m-d H:i:s",strtotime("+".$no_of_min.' minutes',strtotime($checkin_date)));
        $entry->checkout_date = $checkout_date;		
        $entry->checkin_date = $checkin_date;		
		$entry->save();


		if ($entry->type == 1) {
	        $table = 'pods';
	    } elseif ($entry->type == 2) {
	        $table = 'single_cabins';
	    } else {
	        $table = 'double_beds';
	    }

	    $availableIds = DB::table($table)
	        ->where('client_id', $entry->client_id)
	        ->whereNotIn('id', function ($q) use ($entry, $checkin_date, $checkout_date) {
	            $q->select('room_id')
	              ->from('room_availability')
	              ->where('room_type', $entry->type)
	              ->where('from_datetime', '<', $checkout_date)
	              ->where('to_datetime', '>', $checkin_date)
	              ->where('status', 1);
	        })
	        ->limit($entry->no_of_rooms)
	        ->pluck('id');

	    if ($availableIds->count() < $entry->no_of_rooms) {
	        DB::rollBack();
	        return response()->json([
	            'success' => false,
	            'message' => 'Rooms not available for selected date & slot'
	        ]);
	    }

	  
	    foreach ($availableIds as $rid) {
	        DB::table('room_availability')->insert([
	            'room_type' => $entry->type,
	            'room_id' => $rid,
	            'booking_id' => $entry->id,
	            'from_datetime' => $entry->checkin_date,
	            'to_datetime' => $entry->checkout_date,
	            'status' => 1,
	            'created_at' => now(),
	            'updated_at' => now()
	        ]);
	    }

	    $entry->e_ids = implode(',', $availableIds->toArray());
	    $entry->save();

		$data['success'] = true;
		$data['message'] = "Successfully Saved";
		$data['entry_id'] = $entry->id;
		return Response::json($data,200,[]);
	}

	public function createOrder(Request $request)
	{
	    $entry_id = $request->entry_id;
	    $entry = Room1::find($entry_id);
	   	
	    $txnId = 'TXN' . time();

	    // $payload = [
	    //     "merchantId" => env('PHONEPE_MERCHANT_ID'),
	    //     "merchantTransactionId" => $txnId,
	    //     "merchantUserId" => "USER_" . ($entry->client_id ? $entry->client_id : 0),
	    //     "amount" => $entry->booking_amount * 100,
	    //     "redirectUrl" => url('/api/payment/callback'),
	    //     "redirectMode" => "POST",
	    //     "callbackUrl" => url('/api/payment/webhook'),
	    //     "mobileNumber" => $entry->mobile_no,
	    //     "paymentInstrument" => [
	    //         "type" => "PAY_PAGE"
	    //     ]
	    // ];

	    // $base64 = base64_encode(json_encode($payload));
	    // $checksum = hash(
	    //     'sha256',
	    //     $base64 . "/pg/v1/pay" . env('PHONEPE_SALT_KEY')
	    // ) . "###" . env('PHONEPE_SALT_INDEX');

	    // $response = Http::withHeaders([
	    //     "Content-Type" => "application/json",
	    //     "X-VERIFY" => $checksum
	    // ])->post(env('PHONEPE_BASE_URL') . "/pg/v1/pay", [
	    //     "request" => $base64
	    // ])->json();

	    $order_id = DB::table("orders")->insertGetId([
	        "txn_id" => $txnId,
	        "room_entry_id" => $entry_id,
	        "name" => ($entry->name)?$entry->name:'',
	        "type" => ($entry->type)?$entry->type:0,
	        "client_id" => ($entry->client_id)?$entry->client_id:0,
	        "status" => 0, // pending
	        "created_at" => now()
	    ]);


        $data['success'] = true;
        $data["redirect_url"] = "https://hotel.aadhyasriwebsolutions.com/thank-you/";
        // $data["redirect_url"] = $response['data']['instrumentResponse']['redirectInfo']['url'];
        $data['order_id'] = $order_id;
        return Response::json($data,200,[]);

        // This will be remove in after

	    // return response()->json([
	    //     "success" => true,
	    //     "redirect_url" => $response['data']['instrumentResponse']['redirectInfo']['url'],
	    //     "order_id" => $order_id
	    // ]);
	}

	public function callback(Request $request){
        return redirect('https://hotel.aadhyasriwebsolutions.com/payment-success');
    }

    public function webhook(Request $request){
	    // $decoded = json_decode(base64_decode($request->response), true);

    	$decoded = [
		    "merchantId" => "PGTESTPAYUAT",
		    "merchantTransactionId" => "TXN1768204733",
		    "amount" => 50000,
		    "state" => "COMPLETED",
		    "responseCode" => "SUCCESS",
		    "paymentInstrument" => [
		        "type" => "UPI"
		    ]
		];

	    $txnId = isset($decoded['merchantTransactionId']) ? $decoded['merchantTransactionId']: null;
	    $state = isset($decoded['state']) ? $decoded['state'] : '';
	    $responseCode = isset($decoded['responseCode']) ? $decoded['responseCode'] : '';

	    if ($txnId && $state === 'COMPLETED' && $responseCode === 'SUCCESS') {

	        DB::table('orders')
				->where('txn_id', $txnId)
			  	->where('status', 0)   // pending only
			  	->update([
			   		'status' => 1,
			  	]);

			$order = DB::table('orders')->where('txn_id', $txnId)->where('status',1)->first();

			if($order){
				DB::table('room_entries')->where('id',$order->room_entry_id)->update(['status'=>1,'payment_status'=>1]);

				DB::table('room_availability')->update([
		            'booking_id' => $order->room_entry_id,
		            'status' => 1,
		        ]);
			}
	    }

	    return response()->json(['status' => 'OK']);
	}

}
