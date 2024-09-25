<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;

class Entry extends Model
{

    public static function setCheckStatus(){
        DB::table("cloakroom_penalities")->where('client_id',Auth::user()->client_id)->where('is_checked', 0)->update(['is_checked'=>1]);
        DB::table("cloakroom_entries")->where('client_id',Auth::user()->client_id)->where('is_checked', 0)->update(['is_checked'=>1]);

        DB::table("sitting_entries")->where('client_id',Auth::user()->client_id)->where('is_checked', 0)->update(['is_checked'=>1]);
        DB::table("e_entries")->where('client_id',Auth::user()->client_id)->where('is_checked', 0)->update(['is_checked'=>1]);
        
        DB::table('check_status')->insert([
            'check_date_time' => date("Y-m-d H:i:s"),
            'checked_by' => Auth::id(),
        ]);
    }

    public static function getServiceIds($client_id){
        return DB::table("client_services")->where('status',1)->where("client_id", $client_id)->pluck('services_id')->toArray();
    }

    public static function payTypes(){
        $ar = [];
        $ar[] = ['value'=>1,'label'=>'Cash'];
        $ar[] = ['value'=>2,'label'=>'UPI'];

        return $ar;
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

    // public static function checkShift($type = 1){
    //     $a_shift = strtotime("06:00:00");
    //     $b_shift =strtotime("14:00:00");
    //     $c_shift =strtotime("22:00:00");

    //     $current_time = strtotime(date("H:i:s"));
    //     // $current_time = "03:09:00";

    //     if($current_time > $a_shift && $current_time < $b_shift){

    //         if($type == 1){
    //             return "A";
    //         } else {
    //             return "C";
    //         }

    //     }else if($current_time > $b_shift && $current_time < $c_shift){
    //         if($type == 1){
    //             return "B";
    //         } else {
    //             return "A";
    //         }
    //     }else{
    //         if($type == 1){
    //             return "C";
    //         } else {
    //             return "B";
    //         }
    //     }

    // }
    public static function checkShift($type = 1){
        $a_shift = strtotime("10:00:00");
        $b_shift =strtotime("22:00:00");
        $current_time = strtotime(date("H:i:s"));
        if($current_time >= $a_shift && $current_time <= $b_shift){
            return "A";
        }else{
            return "B";
        }

    }
    public static function getPDate(){
        $p_date = date("Y-m-d");
        return $p_date;
    }
}