<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\CloakRoom;
use App\Models\User;

class BackupController extends Controller {


	public function dumpData(Request $request){

		$old_entry_ids = DB::table('cloakroom_entries_backup')->where('is_backup',0)->take(500)->pluck('id')->toArray();

		$clients = User::where("client_id", 6)->pluck("id","old_id")->toArray();
		foreach ($old_entry_ids as $key => $old_id) {
			$newTask = (new CloakRoom)
			->setTable('cloakroom_entries_backup')
			->find($old_id)
			->replicate()
			->setTable('cloakroom_entries')
			->save();




			$new_entry = DB::table('cloakroom_entries')->orderBy('id','DESC')->first();

			DB::table('cloakroom_entries')->where('id',$new_entry->id)->update([
				'old_id'=>$old_id,
				'client_id'=>6,
				'added_by'=>$clients[$new_entry->added_by] ? $clients[$new_entry->added_by] : -1,
			]);
		
			$pens = DB::table('cloakroom_penalities_backup')->where('cloakroom_id',$old_id)->get();


			if(sizeof($pens) > 0){
				foreach ($pens as $key => $item) {
					$ins_data = [
						'client_id' => Auth::user()->client_id,
						'cloakroom_id' => $new_entry->id,
						'old_cloakroom_id' => $old_id,
						'paid_amount' => $item->paid_amount,
						'pay_type' => $item->pay_type,
						'shift' => $item->shift,
						'date' => $item->date,
						'current_time' => $item->current_time,
						'added_by' => $item->added_by,
						'is_checked' => $item->is_checked,
						'is_collected' => $item->is_collected,
						'created_at' => $item->created_at,
						'updated_at' => $item->updated_at,
					];

					DB::table('cloakroom_penalities')->insert($ins_data);
				}
			} else {
				dd("Conratulations");
			}
			

			DB::table('cloakroom_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
				'is_backup' => 1,
			]);

			// dd($new_entry);

		}
		dd("Done");

	    // return "Wow";
		// $old_entry_ids = DB::table('users_backup')->pluck('id')->toArray();
		// foreach ($old_entry_ids as $key => $old_id) {
		// 	$newTask = (new User)
		// 	->setTable('users_backup')
		// 	->find($old_id)
		// 	->replicate()
		// 	->setTable('users')
		// 	->save();
		// }

		// $old_entry_ids = DB::table('massage_entries_backup')->where('is_backup',0)->take(1000)->pluck('id')->toArray();
		// foreach ($old_entry_ids as $key => $old_id) {
		// 	$newTask = (new Massage)
		// 	->setTable('massage_entries_backup')
		// 	->find($old_id)
		// 	->replicate()
		// 	->setTable('massage_entries')
		// 	->save();

		// 	DB::table('massage_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 		'is_backup' => 1,
		// 	]);
		// }

		// $old_entry_ids = DB::table('locker_entries_backup')->where('is_backup',0)->take(5000)->pluck('id')->toArray();
		// if(sizeof($old_entry_ids) > 0){
		// 	foreach ($old_entry_ids as $key => $old_id) {
		// 		$newTask = (new Locker)
		// 		->setTable('locker_entries_backup')
		// 		->find($old_id)
		// 		->replicate()
		// 		->setTable('locker_entries')
		// 		->save();

		// 		DB::table('locker_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 			'is_backup' => 1,
		// 		]);
		// 	}
		// 	echo 'done';

		// }else{
		// 	echo 'all done';
		// }

		// $old_entry_ids = DB::table('locker_penalty_backup')->where('is_backup',0)->take(1000)->pluck('id')->toArray();
		// if(sizeof($old_entry_ids) > 0){
		// 	foreach ($old_entry_ids as $key => $old_id) {
		// 		$newTask = (new LockerPen)
		// 		->setTable('locker_penalty_backup')
		// 		->find($old_id)
		// 		->replicate()
		// 		->setTable('locker_penalty')
		// 		->save();

		// 		DB::table('locker_penalty_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 			'is_backup' => 1,
		// 		]);
		// 	}
		// 	echo 'done';

		// }else{
		// 	echo 'all done';
		// }

		// $old_entry_ids = DB::table('sitting_entries_backup')->where('is_backup',0)->take(10000)->pluck('id')->toArray();
		// if(sizeof($old_entry_ids) > 0){
		// 	foreach ($old_entry_ids as $key => $old_id) {
		// 		$newTask = (new Sitting)
		// 		->setTable('sitting_entries_backup')
		// 		->find($old_id)
		// 		->replicate()
		// 		->setTable('sitting_entries')
		// 		->save();

		// 		DB::table('sitting_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 			'is_backup' => 1,
		// 		]);
		// 	}
		// 	echo 'done';

		// }else{
		// 	echo 'all done';
		// }		
	}
}
