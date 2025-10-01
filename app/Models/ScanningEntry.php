<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;

class ScanningEntry extends Model
{

    protected $table = 'scanning_entries';
 

    public static function rateList(){
        return DB::table("scanning_rate_list")->select('incoming_type_id','item_type_id','rate')->where("client_id", Auth::user()->client_id)->get();
    }

    public static function itemTypes(){
        return DB::table('scanning_item_types')->select('id','item_type_name')->get();
    }

    public static function itemCount($type_id=0,$type=0){
        $date = date("Y-m-d");
        $count = 0;
        if($type == 1){
            $count = DB::table('scanning_entries')->where('date',$date)->where('item_type_id',$type_id)->where('client_id',Auth::user()->client_id)->sum('no_of_item');

        }
        if($type == 2){
            $count = DB::table('scanning_entries')->where('date',$date)->where('incoming_type_id',$type_id)->where('client_id',Auth::user()->client_id)->sum('no_of_item');

        }

        return $count;
    }

    public static function getIncomingTypes(){
        $ar = [];
        $ar[] = ['value'=>1,'label'=>'Outword'];
        $ar[] = ['value'=>2,'label'=>'Inword'];

        return $ar;
    }
    public static function showIncomingTypes(){
        return [1=>"Outword",2=>"Inword"];
    }
    public static function getSlipId(){

        $entry = ScanningEntry::select('slip_id')->where('client_id',Auth::user()->client_id)->orderBy('id','DESC')->first();
        $slip_id = 1;
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
            $total_shift_upi = ScanningEntry::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");



            $total_shift_cash = ScanningEntry::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");

            $last_hour_upi_total = ScanningEntry::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

            $last_hour_cash_total = ScanningEntry::where('client_id', $client_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount");
        }else{
            

            $total_shift_upi = ScanningEntry::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->sum("paid_amount");

            

            $total_shift_cash = ScanningEntry::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',1)->sum("paid_amount");


            $last_hour_upi_total = ScanningEntry::where('client_id', $client_id)->where('added_by',$user_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("paid_amount"); 

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
        $data['label'] = "Scanning";
        return $data;
    }
   

    // public static function getChangePayTypeLog($input_date, $user_id){
    //     if($input_date == ''){
    //         $input_date = date("Y-m-d");
    //     }
    //     $change_cash_to_UPI = DB::table("change_pay_type_log")->where("date", date("Y-m-d", strtotime($input_date)))->where("changed_by", $user_id)->where("new_pay_type", 2)->count();
    //     $change_UPI_to_cash = DB::table("change_pay_type_log")->where("date", date("Y-m-d", strtotime($input_date)))->where("changed_by", $user_id)->where("new_pay_type", 1)->count();
    //     $data["change_cash_to_UPI"] = $change_cash_to_UPI;
    //     $data["change_UPI_to_cash"] = $change_UPI_to_cash;
    //     $data["success"] = true;

    //     return $data;

    // }


}