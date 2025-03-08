<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;
use App\Models\User;
use App\Models\Entry;
use App\Models\CloakRoom, App\Models\Sitting, App\Models\Canteen, App\Models\Massage, App\Models\Locker,App\Models\Recliner,App\Models\Room;

class Shift extends Model
{

    public static function getStatus($request=null, $client_id=0, $service_ids=[]){ 
        $input_date = isset($request['input_date']) ? $request['input_date'] : date("Y-m-d");
        if(in_array(!Auth::user()->priv, [2,5] )){
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
            $data = Shift::calculateAmount($sitting_data, $data);
            if($user_id && Auth::user()->priv == 2){
                $data['chage_pay_type_data'] = Sitting::getChangePayTypeLog($input_date, $user_id);
            }
        }

        if(in_array(2, $service_ids)){
            $cloak_data = CloakRoom::totalShiftData($input_date,$user_id,$client_id);
            $data['cloak_data'] = $cloak_data;
            $data = Shift::calculateAmount($cloak_data, $data);
        }
        
        if(in_array(3, $service_ids)){
            $canteen_data = Canteen::totalShiftData($input_date,$user_id,$client_id);
            $data['canteen_data'] = $canteen_data;
            $data = Shift::calculateAmount($canteen_data, $data);
        }       

        if(in_array(4, $service_ids)){
            $massage_data = Massage::totalShiftData($input_date,$user_id,$client_id);
            $data['massage_data'] = $massage_data;
            $data = Shift::calculateAmount($massage_data, $data);
        }       

        if(in_array(5, $service_ids)){
            $locker_data = Locker::totalShiftData($input_date,$user_id,$client_id);
            $data['locker_data'] = $locker_data;
            $data = Shift::calculateAmount($locker_data, $data);
        }

        if(in_array(7, $service_ids)){
            $recliner_data = Recliner::totalShiftData($input_date,$user_id,$client_id);
            $data['recliner_data'] = $recliner_data;
            $data = Shift::calculateAmount($recliner_data, $data);
        }       

        if(in_array(8, $service_ids)){
            $pod_data = Room::totalShiftData(1,$input_date,$user_id,$client_id);
            $data['pod_data'] = $pod_data;
            $data = Shift::calculateAmount($pod_data, $data);

            $singal_cabin_data = Room::totalShiftData(2,$input_date,$user_id,$client_id);
            $data['singal_cabin_data'] = $singal_cabin_data;
            $data = Shift::calculateAmount($singal_cabin_data, $data);  

            $double_bed_data = Room::totalShiftData(3,$input_date,$user_id,$client_id);
            $data['double_bed_data'] = $double_bed_data;
            $data = Shift::calculateAmount($double_bed_data, $data);
        }
        
        return $data;
    }

    public static function calculateAmount($total_data, $data){

        $data['total_shift_upi'] += $total_data['total_shift_upi'];
        $data['total_shift_cash'] += $total_data['total_shift_cash'];
        $data['total_collection'] += $total_data['total_collection'];
        $data['last_hour_upi_total'] += $total_data['last_hour_upi_total'];
        $data['last_hour_cash_total'] += $total_data['last_hour_cash_total'];
        $data['last_hour_total'] += $total_data['last_hour_total'];
        return $data;

    }
    
}