<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;
use App\Models\Entry;

class Locker extends Model
{

    protected $table = 'locker_entries';

    public static function totalShiftData($input_date='',$user_id=0){
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
        }

        $input_date = date("Y-m-d",strtotime($input_date));

        if($user_id == 0 ){
            $total_shift_upi = Locker::where('date',$input_date)->where('deleted',0)->where('pay_type',2)->sum("paid_amount");
            $total_shift_upi += DB::table('locker_penalty')->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");

            $total_shift_cash = Locker::where('date',$input_date)->where('deleted',0)->where('pay_type',1)->sum("paid_amount");
            $total_shift_cash += DB::table('locker_penalty')->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $last_hour_upi_total = Locker::where('date',$input_date)->where('deleted',0)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 
            $last_hour_upi_total += DB::table('locker_penalty')->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 
            
            $last_hour_cash_total = Locker::where('date',$input_date)->where('deleted',0)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount");
            $last_hour_cash_total += DB::table('locker_penalty')->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount");

        }else{
            $total_shift_upi = Locker::where('date',$input_date)->where('added_by',$user_id)->where('deleted',0)->where('pay_type',2)->sum("paid_amount");
            $total_shift_upi += DB::table('locker_penalty')->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',2)->sum("paid_amount");

            $total_shift_cash = Locker::where('date',$input_date)->where('added_by',$user_id)->where('deleted',0)->where('pay_type',1)->sum("paid_amount");
            $total_shift_cash += DB::table('locker_penalty')->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',1)->sum("paid_amount");

            $last_hour_upi_total = Locker::where('date',$input_date)->where('added_by',$user_id)->where('deleted',0)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 
            $last_hour_upi_total += DB::table('locker_penalty')->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 
            
            $last_hour_cash_total = Locker::where('date',$input_date)->where('added_by',$user_id)->where('deleted',0)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount");
            $last_hour_cash_total += DB::table('locker_penalty')->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

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