<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Entry;
use App\Models\Sitting;
use DB;

class CollectedSitting extends Model{

    protected $table = 'collected_sitting';

    public static function setCheckStatus(){
        DB::table("sitting_entries")->where('is_checked', 0)->update(['is_checked'=>1]);
        DB::table("e_entries")->where('is_checked', 0)->update(['is_checked'=>1]);
    }

    public static function penltyCollection($id = 0){
        
        $check_shift = Entry::checkShift();
        $date = Entry::getPDate();

        if($id > 0){
            $penlty = DB::table("e_entries")->where("id", $id)->first();
            $entry = Sitting::find($penlty->entry_id);

            $checkout_time = date("Y-m-d H:i:s",strtotime("-5 min",strtotime($entry->checkout_date)));
            // dd($penlty);
            if($penlty){
                DB::table("collected_e_entries")->insert([
                    "entry_id" => $penlty->entry_id,
                    "e_entry_id" => $penlty->id,
                    "client_id" => $penlty->client_id,
                    "paid_amount" => $penlty->paid_amount,
                    "pay_type" => $penlty->pay_type,
                    "shift" => $penlty->shift,
                    "date" => $penlty->date,
                    "current_time" => $penlty->current_time,
                    "added_by" => $penlty->added_by,
                    "created_at" => date("Y-m-d H:i:s"),
                    "collected" => 0,
                ]);
                DB::table("e_entries")->where("id", $id)->update([
                    'is_collected'=>1,
                ]);

                $entry->is_late = 0;
                $entry->checkout_time = $checkout_time;
                $entry->is_collected = 1;
                $entry->save();
            }
        }
    }

    public static function sittingCollection($request){

        $check_shift = Entry::checkShift();
        $date = Entry::getPDate();
        $rate_list = DB::table("sitting_rate_list")->where("client_id", Auth::user()->client_id)->first();
        if($request->id > 0){
            $entry = Sitting::find($request->id);
           
            if($entry){
                $e_adults = $entry->no_of_adults - $request->no_of_adults;
                $e_child = $entry->no_of_children - $request->no_of_children;
                $e_hours = $entry->hours_occ - $request->hours_occ;

                // if($e_hours );

                $e_amount = 0;

                if($e_hours > 0 && $e_hours < $entry->hours_occ){
                    $e_amount = $e_hours*$rate_list->adult_rate*$entry->no_of_adults + $e_hours*$rate_list->child_rate*$entry->no_of_children;
                }
                // dd($e_amount);

                if($e_adults > 0){
                    $e_amount += $e_hours*$rate_list->adult_rate*$e_adults;
                }

                if($e_child > 0){
                    $e_amount += $e_hours*$rate_list->child_rate*$e_child;
                }
               
                $ins_data = [
                    'entry_id' =>$entry->id,
                    'total_hours' => $entry->total_hours,
                    'hours_occ' => $request->hours_occ,
                    'erase_hours' => $e_hours,

                    'total_adults' => $entry->no_of_adults,
                    'no_of_adults' => $request->no_of_adults,
                    'erase_adults' => $e_adults,

                    'total_children' => $entry->no_of_children,
                    'no_of_children' => $request->no_of_children,
                    'erase_children' => $e_child,

                    "paid_amount" => $entry->total_amount,
                    "collected_amount" => $e_amount,

                    "collected" => 0,
                    "date" => $date,
                    "created_at" => date("Y-m-d H:i:s"),
                    
                ];

                DB::table('collected_sitting')->insert($ins_data);

                $p_amount =  $rate_list->adult_rate*$request->no_of_adults + $rate_list->child_rate*$request->no_of_children;

                $entry->hours_occ = $request->hours_occ;
                $entry->no_of_adults = $request->no_of_adults;
                $entry->no_of_children = $request->no_of_children;
                $entry->paid_amount = $p_amount;
                $entry->is_collected = 1;
                $entry->save();



                $no_of_min = $entry->hours_occ*60;

                $check_in_date = $entry->date." ".$entry->check_in;
                $entry->check_out = date("H:i:s",strtotime("+".$no_of_min." minutes",strtotime($entry->check_in)));
                $entry->checkout_date = date("Y-m-d H:i:s",strtotime("+".$no_of_min." minutes",strtotime($check_in_date)));

                $c_no_min = $no_of_min-4;
                $entry->checkout_time = date("Y-m-d H:i:s",strtotime("+".$c_no_min." minutes",strtotime($check_in_date)));

                

                $entry->save();

                if($entry->hours_occ == 1){
                   DB::table("e_entries")->where('entry_id',$entry->id)->update([
                        'is_collected' => 1,
                    ]);
                }
                // DB::table("e_entries")->where('entry_id',$entry->id)->update([
                //     'is_collected' => 1,
                // ]);

            }


            // if($entry){
            //     DB::table("collected_cloakroom")->insert([
            //         "entry_id"=>$entry->id,
            //         "total_bag"=>$entry->total_bag,
            //         "no_of_bag"=>$no_of_bag,
            //         "erase_bag" => $e_bag,
            //         "paid_amount"=>$entry->paid_amount,
            //         "collected_amount"=>$rate_list->first_rate*$e_bag,
            //         "collected" => 0,
            //         "date" => $date,
            //         "created_at" => date("Y-m-d H:i:s"), 
            //     ]);
            //     $cloakroom_entrie= DB::table("cloakroom_entries")->where('id', $id)->update([
            //         "no_of_bag" => $no_of_bag, 
            //         "paid_amount" => $rate_list->first_rate*$no_of_bag,
            //         'is_collected'=>1,
            //     ]);
            // }
        }
    }
}