<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;

class AppDailyEntry extends Model
{

    protected $table = 'app_daily_entries';

    public static function payTypes(){
        $ar = [];
        $ar[] = ['value'=>1,'label'=>'UPI'];
        $ar[] = ['value'=>2,'label'=>'Cash'];
        return $ar;
    }

    public static function canteenItemsDrop($canteen_id = 0){
        $canteen_items = DB::table("canteen_items")->select("id as value","item_name as label")->where("canteen_id",$canteen_id)->get();

        return $canteen_items;
    }
    public static function canteenItemList($canteen_id = 0){
        $canteen_items = DB::table("canteen_items")->where('canteen_id',$canteen_id)->get();
        foreach ($canteen_items as $key => $canteen_item) {
            $canteen_item->quantity  = 1;
            $canteen_item->paid_amount = $canteen_item->price * $canteen_item->quantity; 
        }

        return $canteen_items;
    }

    public static function itemsList(){
        $items = DB::table('items')->select('items.id as value','items.item_name as label')->get();

        return $items;
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


    public static function getPDate(){
        $p_date = date("Y-m-d");
        return $p_date;
    }

    public static function totalShiftData($canteen_id=0,$user_id,$input_date=''){
        $check_shift = DailyEntry::checkShift();
        
        $total_shift_cash = 0;
        $total_shift_upi = 0;       

        $last_hour_cash_total = 0;
        $last_hour_upi_total = 0;

        $from_time = date('H:00:00');
        $to_time = date('H:59:59');

        $p_date = DailyEntry::getPDate();
        if($input_date == ''){
            $input_date = date("Y-m-d",strtotime($p_date));
        }else{
            $input_date = date("Y-m-d",strtotime($input_date));

        } 
        $total_shift_upi = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',1)->sum("total_amount");

        $total_shift_cash = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',2)->sum("total_amount");


        $last_hour_upi_total = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("total_amount");


        $last_hour_cash_total = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("total_amount");
            

        $total_collection = $total_shift_upi + $total_shift_cash;
        $last_hour_total = $last_hour_upi_total + $last_hour_cash_total;

        $data['total_shift_upi'] = $total_shift_upi;
        $data['total_shift_cash'] = $total_shift_cash;
        $data['total_collection'] = $total_collection;
        $data['last_hour_upi_total'] = $last_hour_upi_total;
        $data['last_hour_cash_total'] = $last_hour_cash_total;
        $data['last_hour_total'] = $last_hour_total;
        $data['check_shift'] = $check_shift;
     
        $data['shift_date'] = date("d-m-Y",strtotime($input_date));


        return $data;
    }

    public static function totalShiftDataAdmin($canteen_id=0,$user_id =0,$input_date=''){
        $check_shift = DailyEntry::checkShift();
        $total_shift_cash = 0;
        $total_shift_upi = 0;       

        $last_hour_cash_total = 0;
        $last_hour_upi_total = 0;

        $from_time = date('H:00:00');
        $to_time = date('H:59:59');

        $p_date = DailyEntry::getPDate();
        if($input_date == ''){
            $input_date = date("Y-m-d",strtotime($p_date));
        }else{
            $input_date = date("Y-m-d",strtotime($input_date));

        } 

        if($user_id == 0){
            $total_shift_upi = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('pay_type',1)->sum("total_amount");

            $total_shift_cash = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('pay_type',2)->sum("total_amount");


            $last_hour_upi_total = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("total_amount");


            $last_hour_cash_total = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("total_amount");
        } else{

            $total_shift_upi = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',1)->sum("total_amount");

            $total_shift_cash = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',2)->sum("total_amount");


            $last_hour_upi_total = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',1)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("total_amount");


            $last_hour_cash_total = DailyEntry::where('canteen_id',$canteen_id)->where('date',$input_date)->where('added_by',$user_id)->where('pay_type',2)->whereBetween('created_at', [date('Y-m-d H:00:00'), date("Y-m-d H:i:s")])->sum("total_amount");


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
        $data['shift_date'] = date("d-m-Y",strtotime($input_date));


        return $data;
    }

}