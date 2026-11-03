<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;
use App\Models\User;
use App\Models\Entry;
use App\Models\CloakRoom, App\Models\Sitting, App\Models\Canteen, App\Models\Massage, App\Models\Locker,App\Models\Recliner,App\Models\Room,App\Models\Rest;



class Shift extends Model{

    public static function getStatusNew($request = null, $client_id = 0, $service_ids = []){

        $input_date = isset($request['input_date'])? $request['input_date']: date("Y-m-d");

        if (!in_array(Auth::user()->priv, [2, 5])) {
            $user_id = Auth::id();

        } else {
            $user_id = isset($request['user_id'])? $request['user_id']: 0;
        }
        $current_shift = Entry::checkShift();

        $data['total_shift_upi'] = 0;
        $data['total_shift_cash'] = 0;
        $data['total_shift_dues'] = 0;
        $data['total_collection'] = 0;

        $data['last_hour_upi_total'] = 0;
        $data['last_hour_cash_total'] = 0;
        $data['last_hour_dues_total'] = 0;
        $data['last_hour_total'] = 0;

        $data_rows = [];

        if (in_array(1, $service_ids)) {
            $sitting_data = Sitting::totalShiftData($input_date,$user_id,$client_id,false);

            $data = Shift::calculateAmount($sitting_data, $data);

            if ($user_id && Auth::user()->priv == 2) {

                $data['chage_pay_type_data'] = Sitting::getChangePayTypeLog($input_date,$user_id);
            }

            $data_rows[] = $sitting_data;
        }


        /*
        |--------------------------------------------------------------------------
        | Cloak Room
        |--------------------------------------------------------------------------
        */

        if (in_array(2, $service_ids)) {

            $cloak_data = CloakRoom::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = Shift::calculateAmount(
                $cloak_data,
                $data
            );

            $data_rows[] = $cloak_data;
        }


        /*
        |--------------------------------------------------------------------------
        | Canteen
        |--------------------------------------------------------------------------
        */

        if (in_array(3, $service_ids)) {

            $canteen_data = Canteen::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = Shift::calculateAmount(
                $canteen_data,
                $data
            );

            $data_rows[] = $canteen_data;
        }


        /*
        |--------------------------------------------------------------------------
        | Massage
        |--------------------------------------------------------------------------
        */

        if (in_array(4, $service_ids)) {

            $massage_data = Massage::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = Shift::calculateAmount(
                $massage_data,
                $data
            );

            $data_rows[] = $massage_data;
        }


        /*
        |--------------------------------------------------------------------------
        | Locker
        |--------------------------------------------------------------------------
        */

        if (in_array(5, $service_ids)) {

            $locker_data = Locker::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = Shift::calculateAmount(
                $locker_data,
                $data
            );

            $data_rows[] = $locker_data;
        }


        /*
        |--------------------------------------------------------------------------
        | Recliner
        |--------------------------------------------------------------------------
        */

        if (in_array(7, $service_ids)) {

            $recliner_data = Recliner::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = Shift::calculateAmount(
                $recliner_data,
                $data
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Room
        |--------------------------------------------------------------------------
        */

        if (in_array(8, $service_ids)) {

            $pod_data = Room::totalShiftData(
                1,
                $input_date,
                $user_id,
                $client_id
            );

            $data_rows[] = $pod_data;

            $data = Shift::calculateAmount(
                $pod_data,
                $data
            );


            $singal_cabin_data = Room::totalShiftData(
                2,
                $input_date,
                $user_id,
                $client_id
            );

            $data_rows[] = $singal_cabin_data;

            $data = Shift::calculateAmount(
                $singal_cabin_data,
                $data
            );


            $double_bed_data = Room::totalShiftData(
                3,
                $input_date,
                $user_id,
                $client_id
            );

            $data_rows[] = $double_bed_data;

            $data = Shift::calculateAmount(
                $double_bed_data,
                $data
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Scanning
        |--------------------------------------------------------------------------
        */

        if (in_array(9, $service_ids)) {

            $scanning_data = ScanningEntry::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = Shift::calculateAmount(
                $scanning_data,
                $data
            );

            $data_rows[] = $scanning_data;
        }


        /*
        |--------------------------------------------------------------------------
        | Rest
        |--------------------------------------------------------------------------
        */

        if (in_array(10, $service_ids)) {

            $rest_data = Rest::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data_rows[] = $rest_data;

            $data = Shift::calculateAmount(
                $rest_data,
                $data
            );
        }


        return $data;
    }

    public static function getStatus($request=null, $client_id=0, $service_ids=[]){ 
        $input_date = isset($request['input_date']) ? $request['input_date'] : date("Y-m-d");

        if(!in_array(Auth::user()->priv, [2,5])){
            $user_id = Auth::id();
        } else{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;
        }
         
        $current_shift = Entry::checkShift();

        $data['total_shift_upi'] = 0;
        $data['total_shift_cash'] = 0;
        $data['total_shift_dues'] = 0;
        $data['total_collection'] = 0;
        $data['last_hour_upi_total'] = 0;
        $data['last_hour_cash_total'] = 0;
        $data['last_hour_dues_total'] = 0;
        $data['last_hour_total'] = 0;

        $data_rows = [];

        if(in_array(1, $service_ids)){
            $sitting_data = Sitting::totalShiftData($input_date,$user_id,$client_id);
            $data = Shift::calculateAmount($sitting_data, $data);

            if($user_id && Auth::user()->priv == 2){
                $data['chage_pay_type_data'] = Sitting::getChangePayTypeLog($input_date, $user_id);
            }

            $data_rows[] = $sitting_data;
        }

        if(in_array(2, $service_ids)){
            $cloak_data = CloakRoom::totalShiftData($input_date,$user_id,$client_id);
            $data = Shift::calculateAmount($cloak_data, $data);

            $data_rows[] = $cloak_data;
        }
        
        if(in_array(3, $service_ids)){
            $canteen_data = Canteen::totalShiftData($input_date,$user_id,$client_id);
            $data = Shift::calculateAmount($canteen_data, $data);

            $data_rows[] = $canteen_data;
        }       

        if(in_array(4, $service_ids)){
            $massage_data = Massage::totalShiftData($input_date,$user_id,$client_id);
            $data = Shift::calculateAmount($massage_data, $data);

            $data_rows[] = $massage_data;
        }       

        if(in_array(5, $service_ids)){
            $locker_data = Locker::totalShiftData($input_date,$user_id,$client_id);
            $data = Shift::calculateAmount($locker_data, $data);

            $data_rows[] = $locker_data;
        }

        if(in_array(7, $service_ids)){
            $recliner_data = Recliner::totalShiftData($input_date,$user_id,$client_id);
            $data = Shift::calculateAmount($recliner_data, $data);

            $data_rows[] = $recliner_data;
        }       

        // if(in_array(8, $service_ids)){
        //     $pod_data = Room::totalShiftData(1,$input_date,$user_id,$client_id,false);
        //     $data_rows[] = $pod_data;
        //     $data = Shift::calculateAmount($pod_data, $data);

        //     $singal_cabin_data = Room::totalShiftData(2,$input_date,$user_id,$client_id,false);
        //     $data_rows[] = $singal_cabin_data;
        //     $data = Shift::calculateAmount($singal_cabin_data, $data);  

        //     $double_bed_data = Room::totalShiftData(3,$input_date,$user_id,$client_id,false);
        //     $data_rows[] = $double_bed_data;
        //     $data = Shift::calculateAmount($double_bed_data, $data);
        // }

        if(in_array(8, $service_ids)){
            $pod_data = Room::totalShiftData(1,$input_date,$user_id,$client_id,false);
            $singal_cabin_data = Room::totalShiftData(2,$input_date,$user_id,$client_id,false);
            $double_bed_data = Room::totalShiftData(3,$input_date,$user_id,$client_id,false);

            if(in_array(Auth::user()->priv, [2,5])){
                $hide_amount = Entry::hideAmount($client_id,$input_date);

                $deduction = min($pod_data['total_shift_cash'],$hide_amount);
                $pod_data['total_shift_cash'] -= $deduction;
                $hide_amount -= $deduction;

                $deduction = min($singal_cabin_data['total_shift_cash'],$hide_amount);
                $singal_cabin_data['total_shift_cash'] -= $deduction;
                $hide_amount -= $deduction;

                $deduction = min($double_bed_data['total_shift_cash'],$hide_amount);
                $double_bed_data['total_shift_cash'] -= $deduction;
                $hide_amount -= $deduction;

                $pod_data['total_collection'] = $pod_data['total_shift_upi'] + $pod_data['total_shift_cash'];
                $singal_cabin_data['total_collection'] = $singal_cabin_data['total_shift_upi'] + $singal_cabin_data['total_shift_cash'];
                $double_bed_data['total_collection'] = $double_bed_data['total_shift_upi'] + $double_bed_data['total_shift_cash'];
            }

            $data_rows[] = $pod_data;
            $data = Shift::calculateAmount($pod_data,$data);

            $data_rows[] = $singal_cabin_data;
            $data = Shift::calculateAmount($singal_cabin_data,$data);

            $data_rows[] = $double_bed_data;
            $data = Shift::calculateAmount($double_bed_data,$data);
        }

        if(in_array(9, $service_ids)){
            $scanning_data = ScanningEntry::totalShiftData($input_date,$user_id,$client_id,false);
            $data = Shift::calculateAmount($scanning_data, $data);

            $data_rows[] = $scanning_data;
        } 

        if(in_array(10, $service_ids)){
            $rest_data = Rest::totalShiftData($input_date,$user_id,$client_id);
            $data_rows[] = $rest_data;

            $data = Shift::calculateAmount($rest_data, $data);
        } 

        return $data;
    }

    public static function getStatusOld($request=null, $client_id=0, $service_ids=[]){ 
        $input_date = isset($request['input_date']) ? $request['input_date'] : date("Y-m-d");
        if(in_array(!Auth::user()->priv, [2,5] )){
            $user_id = Auth::id();
        } else{
            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;
        }
         
        $current_shift = Entry::checkShift();

        $data['total_shift_upi'] = 0;
        $data['total_shift_cash'] = 0;
        $data['total_shift_dues'] = 0;
        $data['total_collection'] = 0;
        $data['last_hour_upi_total'] = 0;
        $data['last_hour_cash_total'] = 0;
        $data['last_hour_dues_total'] = 0;
        $data['last_hour_total'] = 0;

        $data_rows = [];

        if(in_array(1, $service_ids)){
            // $sitting_data = Sitting::totalShiftData($input_date,$user_id,$client_id);
            
            $sitting_data = Sitting::totalShiftData($input_date,$user_id,$client_id,false);

            $data = Shift::calculateAmount($sitting_data, $data);
            if($user_id && Auth::user()->priv == 2){
                $data['chage_pay_type_data'] = Sitting::getChangePayTypeLog($input_date, $user_id);
            }

            $data_rows[] = $sitting_data;
        }

        if(in_array(2, $service_ids)){
            $cloak_data = CloakRoom::totalShiftData($input_date,$user_id,$client_id);
            // $data['cloak_data'] = $cloak_data;
            $data = Shift::calculateAmount($cloak_data, $data);


            $data_rows[] = $cloak_data;
        }
        
        if(in_array(3, $service_ids)){
            $canteen_data = Canteen::totalShiftData($input_date,$user_id,$client_id);
            // $data['canteen_data'] = $canteen_data;
            $data = Shift::calculateAmount($canteen_data, $data);

            $data_rows[] = $canteen_data;
        }       

        if(in_array(4, $service_ids)){
            $massage_data = Massage::totalShiftData($input_date,$user_id,$client_id);
            // $data['massage_data'] = $massage_data;
            $data = Shift::calculateAmount($massage_data, $data);


            $data_rows[] = $massage_data;
        }       

        if(in_array(5, $service_ids)){
            $locker_data = Locker::totalShiftData($input_date,$user_id,$client_id);
            // $data['locker_data'] = $locker_data;
            $data = Shift::calculateAmount($locker_data, $data);
            $data_rows[] = $locker_data;
        }

        if(in_array(7, $service_ids)){
            $recliner_data = Recliner::totalShiftData($input_date,$user_id,$client_id);
            // $data['recliner_data'] = $recliner_data;
            $data = Shift::calculateAmount($recliner_data, $data);

            // $data_rows[] = $recliner_data;
        }       

        if(in_array(8, $service_ids)){
            $pod_data = Room::totalShiftData(1,$input_date,$user_id,$client_id);
            $data_rows[] = $pod_data;
            $data = Shift::calculateAmount($pod_data, $data);

            $singal_cabin_data = Room::totalShiftData(2,$input_date,$user_id,$client_id);
            $data_rows[] = $singal_cabin_data;
            $data = Shift::calculateAmount($singal_cabin_data, $data);  

            $double_bed_data = Room::totalShiftData(3,$input_date,$user_id,$client_id);
            $data_rows[] = $double_bed_data;
            $data = Shift::calculateAmount($double_bed_data, $data);
        }
        if(in_array(9, $service_ids)){
            $scanning_data = ScanningEntry::totalShiftData($input_date,$user_id,$client_id);
            
            $data = Shift::calculateAmount($scanning_data, $data);

            $data_rows[] = $scanning_data;
        } 

        if(in_array(10, $service_ids)){
            $rest_data = Rest::totalShiftData($input_date,$user_id,$client_id);
            $data_rows[] = $rest_data;
            $data = Shift::calculateAmount($rest_data, $data);
        } 
        
        return $data;
    }

    public static function calculateAmount($total_data, $data){

        $data['total_shift_upi'] += $total_data['total_shift_upi'];
        $data['total_shift_cash'] += $total_data['total_shift_cash'];
        $data['total_shift_dues'] += isset($total_data['total_shift_dues'])?$total_data['total_shift_dues']:0;
        $data['total_collection'] += $total_data['total_collection'];
        $data['last_hour_upi_total'] += $total_data['last_hour_upi_total'];
        $data['last_hour_cash_total'] += $total_data['last_hour_cash_total'];
        $data['last_hour_dues_total'] += isset($total_data['last_hour_dues_total'])?$total_data['last_hour_dues_total']:0;
        $data['last_hour_total'] += $total_data['last_hour_total'];
        return $data;
    }
    
}