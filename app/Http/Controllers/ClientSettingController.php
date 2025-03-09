<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Shift;

class ClientSettingController extends Controller {

	public function setAmount(Request $request){
		$sidebar = 'set_amount';
        $subsidebar = 'set_amount';

        

        return view('admin.clients.set_shift_status',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }
    public function shiftStatus(Request $request){
        $sidebar = 'shift_status';
        $subsidebar = 'shift_status';

        

        return view('admin.clients.all_shift_status',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function initAmountSetting(Request $request){
        $clients = DB::table('clients')->select('id','client_name','hide_amount')->where('org_id',1)->get();

        $data['success'] = true;
        $data['clients'] = $clients;

        return Response::json($data,200,[]);
    }

    public function storeAmountSetting(Request $request){
        $clients = $request->clients;

        if(sizeof($clients) > 0) {
            foreach ($clients as $key => $client) {
                DB::table('clients')->where('id',$client['id'])->where('org_id',1)->update([
                    'hide_amount' => $client['hide_amount'],
                ]);
            }
            $data['message'] = "Successfully Updated";

        }else{
            $data['message'] = "Someting Went Wrong";
        }

        $data['success'] = true;

        return Response::json($data,200,[]);
    }

    public function initShiftStatus(Request $request){
        $clients = DB::table('clients')->select('client_name','hide_amount','id')->where('org_id',Auth::user()->org_id)->get();

        $shift_rows = [];

        $grand_total = new \stdClass;
        $grand_total->client_name = 'Grand Total';
        $grand_total->total_collection = 0;
        $grand_total->total_shift_cash = 0;
        $grand_total->total_shift_upi = 0;

        foreach ($clients as $key => $client) {
            $service_ids = DB::table('client_services')->where('client_id',$client->id)->where('services_id','!=',3)->pluck('services_id')->toArray();

            $client->total_collection = 0;
            $client->total_shift_cash = 0;
            $client->total_shift_upi = 0;

            $c_shift = Shift::getStatus($request, $client->id,  $service_ids);
            if($c_shift){

                $client->total_collection = ($c_shift['total_collection'] > $client->hide_amount)?$c_shift['total_collection'] - $client->hide_amount: $c_shift['total_collection'];

                $client->total_shift_cash = ($c_shift['total_shift_cash'] > $client->hide_amount)?$c_shift['total_shift_cash'] - $client->hide_amount : $c_shift['total_shift_cash'];
                
                $client->total_shift_upi = $c_shift['total_shift_upi'];

                // $client->total_shift_cash = $c_shift['total_shift_cash'] - $client->hide_amount;
                // $client->total_shift_upi = $c_shift['total_shift_upi'];
            }

            $grand_total->total_collection += $client->total_collection;
            $grand_total->total_shift_cash += $client->total_shift_cash;
            $grand_total->total_shift_upi += $client->total_shift_upi;


            array_push($shift_rows,$client);
        }

        array_push($shift_rows,$grand_total);

        $data['success'] = true;
        $data['shift_rows'] = $shift_rows;

        return Response::json($data,200,[]);

    }

}


