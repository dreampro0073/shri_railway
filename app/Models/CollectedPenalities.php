<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Entry;
use App\Models\CloakRoom;
use DB;

class CollectedPenalities extends Model{

    protected $table = 'collected_penalities';

    public static function setCheckStatus(){
        DB::table("cloakroom_penalities")->where('is_checked', 0)->update(['is_checked'=>1]);
        DB::table("cloakroom_entries")->where('is_checked', 0)->update(['is_checked'=>1]);
    }

    public static function penltyCollection($id = 0){
        $check_shift = Entry::checkShift();
        $date = Entry::getPDate();
        if($id > 0){
            $penlty = DB::table("cloakroom_penalities")->where("id", $id)->first();
            $entry = DB::table("cloakroom_entries")->where("id", $penlty->cloakroom_id)->first();

            $checkout_time = date("Y-m-d H:i:s",strtotime("- 50 min",strtotime($entry->checkout_date)));

            if($penlty){
                DB::table("collected_penalities")->insert([
                    "penlty_id" => $penlty->id,
                    "client_id" => $penlty->client_id,
                    "cloakroom_id" => $penlty->cloakroom_id,
                    "paid_amount" => $penlty->paid_amount,
                    "pay_type" => $penlty->pay_type,
                    "shift" => $penlty->shift,
                    "date" => $penlty->date,
                    "current_time" => $penlty->current_time,
                    "added_by" => $penlty->added_by,
                    "is_checked" => $penlty->is_checked,
                    "p_created_at" => $penlty->created_at,
                    "p_updated_at" => $penlty->updated_at,
                    "created_at" => date("Y-m-d H:i:s"),
                    "collected" => 0,
                ]);
                DB::table("cloakroom_penalities")->where("id", $id)->update([
                    'is_collected'=>1,
                ]);
                DB::table("cloakroom_entries")->where("id", $penlty->cloakroom_id)->update([
                    'is_late'=>0,
                    'no_of_day' => 1,
                    'collected_pen' => 1,
                    'checkout_time' => $checkout_time, 
                ]);
            }
        }
    }

    public static function cloakroomCollection($id = 0,$no_of_bag=0){
        $check_shift = Entry::checkShift();
        $date = Entry::getPDate();
        $rate_list = DB::table("cloakroom_rate_list")->where("client_id", Auth::user()->client_id)->first();
        if($id > 0){
            $entry = DB::table("cloakroom_entries")->where("id", $id)->first();
            $e_bag = $entry->total_bag - $no_of_bag;
            if($entry){
                DB::table("collected_cloakroom")->insert([
                    "entry_id"=>$entry->id,
                    "total_bag"=>$entry->total_bag,
                    "no_of_bag"=>$no_of_bag,
                    "erase_bag" => $e_bag,
                    "paid_amount"=>$entry->paid_amount,
                    "collected_amount"=>$rate_list->first_rate*$e_bag,
                    "collected" => 0,
                    "date" => $date,
                    "created_at" => date("Y-m-d H:i:s"), 
                ]);
                $cloakroom_entrie= DB::table("cloakroom_entries")->where('id', $id)->update([
                    "no_of_bag" => $no_of_bag, 
                    "paid_amount" => $rate_list->first_rate*$no_of_bag,
                    'is_collected'=>1,
                ]);
            }
        }
    }
}