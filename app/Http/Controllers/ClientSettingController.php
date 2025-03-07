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
		$sidebar = 'clients';
        $subsidebar = 'client-setting';

        

        return view('admin.clients.set_shift_status',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }
    public function shiftStatus(Request $request){
        $sidebar = 'clients';
        $subsidebar = 'shift-status';

        

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

        foreach ($clients as $key => $client) {
            $service_ids = DB::table('client_services')->where('client_id',$client->id)->where('services_id','!=',3)->pluck('services_id')->toArray();

            $c_shift = Shift::getStatus($request, $client->id,  $service_ids);

            // foreach ($c_shift as $key => $shift) {
                
            //     $client->total_collection = $shift->total_collection;
            //     $client->total_shift_cash = $shift->total_shift_cash;
            //     $client->total_shift_upi = $shift->total_shift_upi;

            // }

            if($c_shift){
                $client->total_collection = $c_shift->total_collection;
                $client->total_shift_cash = $c_shift->total_shift_cash;
                $client->total_shift_upi = $c_shift->total_shift_upi;
            }
        }

        $data['success'] = true;
        $data['clients'] = $clients;

        return Response::json($data,200,[]);

    }

}


