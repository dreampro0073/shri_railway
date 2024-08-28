<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;

class Canteen extends Model
{

    protected $table = 'canteens';

        public static function totalShiftData($input_date= "", $user_id=0){
        $check_shift = Entry::checkShift();
        
        $total_shift_cash = 0;
        $total_shift_upi = 0;       

        $last_hour_cash_total = 0;
        $last_hour_upi_total = 0;

        $from_time = date('H:00:00');
        $to_time = date('H:59:59');

        $p_date = Entry::getPDate();

        $shift_date = date("d-m-Y",strtotime($p_date));

        if($input_date == ''){
            $input_date = date("Y-m-d");
        }else{
            $input_date = date("Y-m-d",strtotime($input_date));
        }
        
        $client_id = Auth::user()->client_id;
        if(Auth::user()->priv != 2){
            $user_id = Auth::id();
        }

        
        if($user_id == 0){
            $total_shift_upi = DB::table("daily_entries")->where('date',$input_date)->where("client_id", $client_id)->where('pay_type',2)->sum("total_amount");
            

            $total_shift_cash = DB::table("daily_entries")->where('date',$input_date)->where("client_id", $client_id)->where('pay_type',1)->sum("total_amount");
            

            $last_hour_upi_total = DB::table("daily_entries")->where('date',$input_date)->where("client_id", $client_id)->where('pay_type',2)->whereBetween('check_in', [$from_time, $to_time])->sum("total_amount"); 
            
            
            $last_hour_cash_total = DB::table("daily_entries")->where('date',$input_date)->where("client_id", $client_id)->where('pay_type',1)->whereBetween('check_in', [$from_time, $to_time])->sum("total_amount");
            
        }else{
            $total_shift_upi = DB::table("daily_entries")->where('date',$input_date)->where('added_by',$user_id)->where("client_id", $client_id)->where('pay_type',2)->sum("total_amount");
           

            $total_shift_cash = DB::table("daily_entries")->where('date',$input_date)->where('added_by',$user_id)->where("client_id", $client_id)->where('pay_type',1)->sum("total_amount");
           
            $last_hour_upi_total = DB::table("daily_entries")->where('date',$input_date)->where('added_by',$user_id)->where("client_id", $client_id)->where('pay_type',2)->whereBetween('check_in', [$from_time, $to_time])->sum("total_amount"); 

            
            $last_hour_cash_total = DB::table("daily_entries")->where('date',$input_date)->where('added_by',$user_id)->where("client_id", $client_id)->where('pay_type',1)->whereBetween('check_in', [$from_time, $to_time])->sum("total_amount");
        }


        $total_collection = $total_shift_upi + $total_shift_cash;
        $last_hour_total = $last_hour_upi_total + $last_hour_cash_total;

        $data['total_shift_upi'] = $total_shift_upi;
        $data['total_shift_cash'] = $total_shift_cash;
        $data['total_collection'] = $total_collection;

        $data['last_hour_upi_total'] = $last_hour_upi_total;
        $data['last_hour_cash_total'] = $last_hour_cash_total;
        $data['last_hour_total'] = $last_hour_total;
        $data['check_shift'] = $check_shift;
        $data['shift_date'] = $shift_date;

        return $data;
    }

}