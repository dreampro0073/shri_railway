<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;

class Sitting extends Model
{

    protected $table = 'sitting_entries';

    public static function payTypes(){
        $ar = [];
        $ar[] = ['value'=>1,'label'=>'Cash'];
        $ar[] = ['value'=>2,'label'=>'UPI'];

        return $ar;
    }

    public static function eSum($entry_id=0){
        return DB::table('e_entries')->where('is_collected',0)->where('entry_id',$entry_id)->sum('paid_amount');
    }

    public static function rateList(){
        return DB::table("sitting_rate_list")->where("client_id", Auth::user()->client_id)->first();
    }

    public static function getAvailLockers(){
        return DB::table('lockers')->where('status',0)->get();
    }

    public static function showPayTypes(){
        return [1=>'Cash',2=>"UPI"];
    }

    public static function hours(){
        $ar = [];
        for ($i=1; $i <= 24; $i++) { 
           $ar[] = ['value'=>$i,'label'=>$i];
        }
        return $ar;
    }

    public static function days(){
        $ar = [];
        for ($i=1; $i <= 15; $i++) { 
           $ar[] = ['value'=>$i,'label'=>$i];
        }
        return $ar;
    }

    public static function checkShift($type = 1){
        $a_shift = strtotime("06:00:00");
        $b_shift =strtotime("14:00:00");
        $c_shift =strtotime("22:00:00");

        $current_time = strtotime(date("H:i:s"));

        if($current_time > $a_shift && $current_time < $b_shift){

            if($type == 1){
                return "A";
            } else {
                return "C";
            }

        }else if($current_time > $b_shift && $current_time < $c_shift){
            if($type == 1){
                return "B";
            } else {
                return "A";
            }
        }else{
            if($type == 1){
                return "C";
            } else {
                return "B";
            }
        }

    }

    public static function getSlipId(){

        $entry = Sitting::select('slip_id')->where('client_id',Auth::user()->client_id)->orderBy('id','DESC')->first();
        $slip_id = Auth::user()->client_id.'1';
        if($entry){
            $slip_id = $entry->slip_id+1;
        }
        return $slip_id;
    }
    public static function totalShiftData($input_date='',$user_id=0){
        $check_shift = Entry::checkShift();
        $client_id = Auth::user()->client_id;
        
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
            $total_shift_upi = Sitting::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");



            $total_shift_upi += DB::table('e_entries')->where('is_collected', '!=', 1)->where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");



            $total_shift_cash = Sitting::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $total_shift_cash += DB::table('e_entries')->where('is_collected', '!=', 1)->where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $last_hour_upi_total = Sitting::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_upi_total += DB::table('e_entries')->where('is_collected', '!=', 1)->where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_cash_total = Sitting::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount");

            $last_hour_cash_total += DB::table('e_entries')->where('is_collected', '!=', 1)->where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

        }else{
            // dd($user_id);

            $total_shift_upi = Sitting::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");


            $total_shift_upi += DB::table('e_entries')->where('is_collected', '!=', 1)->where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");
            

            $total_shift_cash = Sitting::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $total_shift_cash += DB::table('e_entries')->where('is_collected', '!=', 1)->where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            // dd($total_shift_cash);


            $last_hour_upi_total = Sitting::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_upi_total += DB::table('e_entries')->where('is_collected', '!=', 1)->where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_cash_total = Sitting::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount");

            $last_hour_cash_total += DB::table('e_entries')->where('is_collected', '!=', 1)->where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

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
    public static function getPDate(){
        $p_date = date("Y-m-d");
        return $p_date;
    }


}