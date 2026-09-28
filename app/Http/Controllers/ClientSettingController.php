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
        $hide_date = $request->input('hide_date');

        if (!$hide_date) {
            $hide_date = date('Y-m-d');
        } else {
            $hide_date = date('Y-m-d', strtotime($hide_date));
        }

        $clients = DB::table('clients')
            ->select(
                'clients.id',
                'clients.client_name',
                DB::raw('IFNULL(client_hide_amounts.hide_amount,0) as hide_amount')
            )
            ->leftJoin('client_hide_amounts', function($join) use ($hide_date) {

                $join->on(
                    'client_hide_amounts.client_id',
                    '=',
                    'clients.id'
                )
                ->where(
                    'client_hide_amounts.hide_date',
                    '=',
                    $hide_date
                );

            })
            ->where('clients.org_id', 1)
            ->orderBy('clients.client_name')
            ->get();

        return Response::json([
            'success' => true,
            'clients' => $clients,
            'hide_date' => $hide_date
        ], 200, []);
    }

    public function storeAmountSetting(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'hide_date' => 'required|date_format:Y-m-d',
                'clients' => 'required|array'
            ]
        );

        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 200, []);
        }

        $clients = $request->input('clients', []);

        $hide_date = date(
            'Y-m-d',
            strtotime($request->input('hide_date'))
        );

        if (sizeof($clients) == 0) {

            return Response::json([
                'success' => false,
                'message' => 'No changes found.'
            ], 200, []);

        }

        DB::beginTransaction();

        try {

            foreach ($clients as $client) {

                if (!isset($client['id'])) {
                    continue;
                }

                $client_id = (int) $client['id'];

                $valid_client = DB::table('clients')
                    ->where('id', $client_id)
                    ->where('org_id', 1)
                    ->first();

                if (!$valid_client) {
                    continue;
                }

                $hide_amount = isset($client['hide_amount'])
                    ? (int) $client['hide_amount']
                    : 0;

                if ($hide_amount < 0) {
                    $hide_amount = 0;
                }

                $check = DB::table('client_hide_amounts')
                    ->where('client_id', $client_id)
                    ->where('hide_date', $hide_date)
                    ->first();

                if ($check) {

                    DB::table('client_hide_amounts')
                        ->where('id', $check->id)
                        ->update([
                            'hide_amount' => $hide_amount,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                } else {

                    DB::table('client_hide_amounts')
                        ->insert([
                            'client_id' => $client_id,
                            'hide_amount' => $hide_amount,
                            'hide_date' => $hide_date,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                }

            }

            DB::commit();

            return Response::json([
                'success' => true,
                'message' => 'Successfully Updated'
            ], 200, []);

        } catch (\Exception $e) {

            DB::rollBack();

            return Response::json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 200, []);

        }
    }

    public function initAmountSetting29Old(Request $request){
        $hide_date = $request->input('hide_date');

        if(!$hide_date){
            $hide_date = date('Y-m-d');
        }else{
            $hide_date = date('Y-m-d', strtotime($hide_date));
        }

        $clients = DB::table('clients')->select('clients.id','clients.client_name',DB::raw('IFNULL(client_hide_amounts.hide_amount,0) as hide_amount'))->leftJoin('client_hide_amounts',function($join) use ($hide_date){
            $join->on('client_hide_amounts.client_id','=','clients.id')->where('client_hide_amounts.hide_date','=',$hide_date);
        })->where('clients.org_id',1)->get();

        $data['success'] = true;
        $data['clients'] = $clients;
        $data['hide_date'] = $hide_date;

        return Response::json($data,200,[]);
    }

    public function storeAmountSetting29Old(Request $request){
        $clients = $request->input('clients',[]);
        $hide_date = $request->input('hide_date');

        if(!$hide_date){
            $hide_date = date('Y-m-d');
        }else{
            $hide_date = date('Y-m-d', strtotime($hide_date));
        }



        if(sizeof($clients) > 0){
            foreach($clients as $client){
                $hide_amount = isset($client['hide_amount']) ? (int)$client['hide_amount'] : 0;

                if($hide_amount < 0){
                    $hide_amount = 0;
                }

                // DB::table('client_hide_amounts')->updateOrInsert(
                //     [
                //         'client_id' => $client['id'],
                //         'hide_date' => $hide_date
                //     ],
                //     [
                //         'hide_amount' => $hide_amount,
                //         'updated_at' => date('Y-m-d H:i:s')
                //     ]
                // );

                $check = DB::table('client_hide_amounts')->where('client_id',$client['id'])->where('hide_date',$hide_date)->first();
                if($check){
                    DB::table('client_hide_amounts')->where('id',$check->id)->update([
                            'hide_amount' => $hide_amount,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                }else{
                    DB::table('client_hide_amounts')->insert([
                        'client_id' => $client['id'],
                        'hide_amount' => $hide_amount,
                        'hide_date' => $hide_date,
                        
                    ]);
                }
            }

            $data['success'] = true;
            $data['message'] = "Successfully Updated";
        }else{
            $data['success'] = false;
            $data['message'] = "Something Went Wrong";
        }

        return Response::json($data,200,[]);
    }


    public function initShiftStatus(Request $request){
        $input_date = $request->input('input_date');

        if(!$input_date){
            $input_date = date('Y-m-d');
        }else{
            $input_date = date('Y-m-d', strtotime($input_date));
        }

        $clients = DB::table('clients')->select('clients.client_name','clients.id',DB::raw('IFNULL(client_hide_amounts.hide_amount,0) as hide_amount'))->leftJoin('client_hide_amounts',function($join) use ($input_date){
            $join->on('client_hide_amounts.client_id','=','clients.id')->where('client_hide_amounts.hide_date','=',$input_date);
        })->where('clients.org_id',Auth::user()->org_id)->get();

        $shift_rows = [];

        $grand_total = new \stdClass;
        $grand_total->client_name = 'Grand Total';
        $grand_total->total_collection = 0;
        $grand_total->total_shift_cash = 0;
        $grand_total->total_shift_upi = 0;
        $grand_total->total_shift_dues = 0;

        $request->merge([
            'input_date' => $input_date
        ]);

        foreach($clients as $key => $client){
            $service_ids = DB::table('client_services')->where('client_id',$client->id)->where('services_id','!=',3)->pluck('services_id')->toArray();

            $client->total_collection = 0;
            $client->total_shift_cash = 0;
            $client->total_shift_upi = 0;
            $client->total_shift_dues = 0;

            $c_shift = Shift::getStatus($request,$client->id,$service_ids);

            if($c_shift){
                $client->total_shift_cash = isset($c_shift['total_shift_cash']) ? $c_shift['total_shift_cash'] : 0;
                $client->total_shift_upi = isset($c_shift['total_shift_upi']) ? $c_shift['total_shift_upi'] : 0;
                $client->total_shift_dues = isset($c_shift['total_shift_dues']) ? $c_shift['total_shift_dues'] : 0;

                if(Auth::user()->priv == 2){
                    $client->total_shift_cash = $client->total_shift_cash - $client->hide_amount;

                    if($client->total_shift_cash < 0){
                        $client->total_shift_cash = 0;
                    }
                }

                $client->total_collection = $client->total_shift_cash + $client->total_shift_upi;

                if(isset($client->total_shift_dues)){
                    $client->total_collection += $client->total_shift_dues;
                }
            }

            $grand_total->total_collection += $client->total_collection;
            $grand_total->total_shift_cash += $client->total_shift_cash;
            $grand_total->total_shift_upi += $client->total_shift_upi;
            $grand_total->total_shift_dues += $client->total_shift_dues;

            array_push($shift_rows,$client);
        }

        array_push($shift_rows,$grand_total);

        $data['success'] = true;
        $data['shift_rows'] = $shift_rows;

        return Response::json($data,200,[]);
    }
    public function initAmountSettingOld(Request $request){
        $clients = DB::table('clients')->select('id','client_name','hide_amount')->where('org_id',1)->get();

        $data['success'] = true;
        $data['clients'] = $clients;

        return Response::json($data,200,[]);
    }

    public function storeAmountSettingOld(Request $request){
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

    public function initShiftStatusOld(Request $request){
        $clients = DB::table('clients')->select('client_name','hide_amount','id')->where('org_id',Auth::user()->org_id)->get();

        $shift_rows = [];

        $grand_total = new \stdClass;
        $grand_total->client_name = 'Grand Total';
        $grand_total->total_collection = 0;
        $grand_total->total_shift_cash = 0;
        $grand_total->total_shift_upi = 0;
        $grand_total->total_shift_dues = 0;

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
                $client->total_shift_dues = $c_shift['total_shift_dues'];
            }

            $grand_total->total_collection += $client->total_collection;
            $grand_total->total_shift_cash += $client->total_shift_cash;
            $grand_total->total_shift_upi += $client->total_shift_upi;
            $grand_total->total_shift_dues += $client->total_shift_dues;


            array_push($shift_rows,$client);
        }

        array_push($shift_rows,$grand_total);

        $data['success'] = true;
        $data['shift_rows'] = $shift_rows;

        return Response::json($data,200,[]);

    }

}


