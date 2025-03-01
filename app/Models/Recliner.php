<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;

class Recliner extends Model
{

    protected $table = 'recliners_entries';

     

    public static function eSum($entry_id=0){
        return DB::table('recliner_e_entries')->where('entry_id',$entry_id)->sum('paid_amount');
    }

    public static function rateList(){
        return DB::table("recliner_rate_list")->where("client_id", Auth::user()->client_id)->first();
    }

    public static function getAvailRecliners(){
        return DB::table('recliners')->where("client_id", Auth::user()->client_id)->where('status',0)->get();
    }
    public static function getAvailReclinersAr(){
        return DB::table('recliners')->where("client_id", Auth::user()->client_id)->where('status',1)->pluck('sl_no')->toArray();
    }
    public static function getBookedReclinersAr(){
        return DB::table('recliners')->where("client_id", Auth::user()->client_id)->where('status',1)->pluck('sl_no')->toArray();
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

    public static function getEnos($rec_ids){

        $rec_ids = explode(',', $rec_ids);
        $e_nos = '';
        $st = DB::table('recliners')->whereIn('id',$rec_ids)->pluck('sl_no')->toArray();
        $e_nos = implode(',', $st);

        return $e_nos;
    }

    public static function getSlipId(){

        $entry = Recliner::select('slip_id')->where('client_id',Auth::user()->client_id)->orderBy('id','DESC')->first();
        $slip_id = Auth::user()->client_id.'1';
        if($entry){
            $slip_id = $entry->slip_id+1;
        }
        return $slip_id;
    }

    public static function totalShiftData($input_date='',$user_id=0, $client_id){
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
            $total_shift_upi = Recliner::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");

            $total_shift_upi += DB::table('recliner_e_entries')->where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");

            $total_shift_cash = Recliner::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $total_shift_cash += DB::table('recliner_e_entries')->where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $last_hour_upi_total = Recliner::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_upi_total += DB::table('recliner_e_entries')->where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_cash_total = Recliner::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount");

            $last_hour_cash_total += DB::table('recliner_e_entries')->where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

        }else{

            $total_shift_upi = Recliner::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");


            $total_shift_upi += DB::table('recliner_e_entries')->where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");
            

            $total_shift_cash = Recliner::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $total_shift_cash += DB::table('recliner_e_entries')->where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $last_hour_upi_total = Recliner::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_upi_total += DB::table('recliner_e_entries')->where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_cash_total = Recliner::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount");

            $last_hour_cash_total += DB::table('recliner_e_entries')->where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

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

    public static function getChangePayTypeLog($input_date, $user_id){
        if($input_date == ''){
            $input_date = date("Y-m-d");
        }
        $change_cash_to_UPI = DB::table("change_pay_type_log")->where("date", date("Y-m-d", strtotime($input_date)))->where("changed_by", $user_id)->where("new_pay_type", 2)->count();
        $change_UPI_to_cash = DB::table("change_pay_type_log")->where("date", date("Y-m-d", strtotime($input_date)))->where("changed_by", $user_id)->where("new_pay_type", 1)->count();
        $data["change_cash_to_UPI"] = $change_cash_to_UPI;
        $data["change_UPI_to_cash"] = $change_UPI_to_cash;
        $data["success"] = true;

        return $data;

    }


}