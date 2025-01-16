<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\CloakRoom;
use App\Models\User;
use App\Models\Sitting;

class BackupController extends Controller {


	public function dumpData(Request $request){

		// $entries = DB::table('cloakroom_entries')->select('unique_id',"id")->where('date','>','2024-12-15')->where('client_id',7)->get();
		// foreach ($entries as $key => $entry) {
		// 	DB::table('cloakroom_entries')->where('id',$entry->id)->update([
		// 	 	'barcodevalue' => bin2hex($entry->unique_id),
		// 	]);
		// }

		// $old_entry_ids = DB::table('cloakroom_entries_backup')->where('is_backup',0)->take(1000)->pluck('id')->toArray();

		// if(sizeof($old_entry_ids) == 0){
		// 	dd("Conratulations");
		// }

		// $clients = User::where("client_id", 7)->pluck("id","old_id")->toArray();
		// foreach ($old_entry_ids as $key => $old_id) {
		// 	$newTask = (new CloakRoom)
		// 	->setTable('cloakroom_entries_backup')
		// 	->find($old_id)
		// 	->replicate()
		// 	->setTable('cloakroom_entries')
		// 	->save();




		// 	$new_entry = DB::table('cloakroom_entries')->orderBy('id','DESC')->first();

		// 	DB::table('cloakroom_entries')->where('id',$new_entry->id)->update([
		// 		'old_id'=>$old_id,
		// 		'client_id'=>7,
		// 		'added_by'=>$clients[$new_entry->added_by] ? $clients[$new_entry->added_by] : -1,
		// 	]);
		
		// 	$pens = DB::table('cloakroom_penalities_backup')->where('cloakroom_id',$old_id)->get();


		// 	if(sizeof($pens) > 0){
		// 		foreach ($pens as $key => $item) {
		// 			$ins_data = [
		// 				'client_id' => Auth::user()->client_id,
		// 				'cloakroom_id' => $new_entry->id,
		// 				'old_cloakroom_id' => $old_id,
		// 				'paid_amount' => $item->paid_amount,
		// 				'pay_type' => $item->pay_type,
		// 				'shift' => $item->shift,
		// 				'date' => $item->date,
		// 				'current_time' => $item->current_time,
		// 				'added_by' => $item->added_by,
		// 				'is_checked' => $item->is_checked,
		// 				'is_collected' => $item->is_collected,
		// 				'created_at' => $item->created_at,
		// 				'updated_at' => $item->updated_at,
		// 			];

		// 			DB::table('cloakroom_penalities')->insert($ins_data);
		// 		}
		// 	} 
			

		// 	DB::table('cloakroom_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
		// 		'is_backup' => 1,
		// 	]);

			

		// }

		// dd("Done");
		

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

		$clients = User::where("client_id", 8)->pluck("id","old_id")->toArray();

		// dd($clients);


		$old_entry_ids = DB::table('sitting_entries_backup')->where('is_backup',0)->where('client_id',8)->take(1)->pluck('id')->toArray();
		if(sizeof($old_entry_ids) > 0){
			foreach ($old_entry_ids as $key => $old_id) {
				$newTask = (new Sitting)
				->setTable('sitting_entries_backup')
				->find($old_id)
				->replicate()
				->setTable('sitting_entries')
				->save();

				DB::table('sitting_entries_backup')->where('client_id',8)->where('is_backup',0)->where('id',$old_id)->update([
					'is_backup' => 1,
				]);

				$new_entry = DB::table('sitting_entries')->where('client_id',8)->orderBy('id','DESC')->first();

				// dd($new_entry);

				DB::table('sitting_entries')->where('id',$new_entry->id)->update([
					'old_id'=>$old_id,
					'client_id'=>8,
					'added_by'=>isset($clients[$new_entry->added_by]) ? $clients[$new_entry->added_by] : -1,
				]);

				$pens = DB::table('e_entries_backup')->where("entry_id",$old_id)->where("client_id",8)->get();

				// dd($pens);


				if(sizeof($pens) > 0){
					foreach ($pens as $key => $item) {
						$ins_data = [
							'client_id' => $item->client_id,
							'entry_id' => $new_entry->id,
							'old_entry_id' => $old_id,
							'paid_amount' => $item->paid_amount,
							'pay_type' => $item->pay_type,
							'type' => $item->type,
							'shift' => $item->shift,
							'date' => $item->date,
							'current_time' => $item->current_time,
							'added_by' => $new_entry->added_by,
							'is_checked' => $item->is_checked,
							'is_collected' => $item->is_collected,
							'created_at' => $item->created_at,
							'updated_at' => $item->updated_at,
						];

						DB::table('e_entries')->insert($ins_data);
					}
				} 
				

				DB::table('sitting_entries_backup')->where('is_backup',0)->where('id',$old_id)->update([
					'is_backup' => 1,
				]);
			}
			echo 'done';

		}else{
			echo 'all done';
		}		
	}
}
