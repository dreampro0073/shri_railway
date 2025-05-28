<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Redirect, Validator, Hash, Response, Session, DB;

class SuperAdminController extends Controller {

	public function dashboard(Request $request){
		$clients = DB::table("clients")->count();
		return view('super_admin.dashboard', [
            "sidebar" => "dashboard",
            "subsidebar" => "dashboard",
            "total_clients" => $clients,
        ]);
	}


	public function clients(){
		return view('super_admin.clients', [
            "sidebar" => "clients",
            "subsidebar" => "clients",
        ]);
	}	

	public function clientAdd($client_id = 0){
		return view('super_admin.client_form', [
            "sidebar" => "clients",
            "subsidebar" => "clients",
            "client_id" => $client_id,
        ]);
	}

	public function getClients(){

		$clients = DB::table("clients")->get();

		foreach ($clients as $client) {
			$users = DB::table("users")->where("client_id", $client->id)->count();
			$client->no_of_users = $users;
		}

		$data["success"] = true;
		$data["clients"] = $clients;
		return Response::json($data, 200, []);
	}	

	public function editInit(Request $request){

		$client = DB::table("clients")->where("id", $request->client_id)->first();

		if($client){
			$client->no_of_users = DB::table("users")->where("client_id", $client->id)->count();
			$services = DB::table("client_services")->select("client_services.*", "services.name")->leftJoin("services", "services.id", "=", "client_services.services_id")->where("client_services.client_id", $request->client_id)->get();
			foreach ($services as $service) {
				if($service->services_id == 1){ //Sittinng
					$service->rate_list = DB::table("sitting_rate_list")->where("client_id", $client->id)->first();

				}

				if($service->services_id == 2){ //Cloakroom
					$service->rate_list = DB::table("cloakroom_rate_list")->where("client_id", $client->id)->first();
				}				

				if($service->services_id == 3){ //Canteen
					$service->rate_list = DB::table("sitting_rate_list")->where("client_id", $client->id)->first();

				}

				if($service->services_id == 4){ //Massage
					$service->rate_list = DB::table("massage_rate_list")->where("client_id", $client->id)->first();

				}							

				if($service->services_id == 5){ // Locker
					$service->rate_list = DB::table("locker_rate_list")->where("client_id", $client->id)->first();

				}						

				if($service->services_id == 7){ //Recliners
					$service->rate_list = DB::table("recliner_rate_list")->where("client_id", $client->id)->first();

				}

				if($service->services_id == 9){ //Scanning
					$rate_list = DB::table("scanning_rate_list")->where("client_id", $client->id)->get();
					$service->rate_list = new \stdClass();
					foreach ($rate_list as $rate) {
						if($rate->item_type_id == 1 && $rate->incoming_type_id == 1){
							$service->rate_list->outword1_rate = $rate->rate;
						}
						if($rate->item_type_id == 1 && $rate->incoming_type_id == 2){
							$service->rate_list->inword1_rate = $rate->rate; 
						}
						if($rate->item_type_id == 2 && $rate->incoming_type_id == 1){
							$service->rate_list->outword2_rate = $rate->rate; 
						}
						if($rate->item_type_id == 2 && $rate->incoming_type_id == 2){
							$service->rate_list->inword2_rate = $rate->rate; 
						}
					}

				}
			}

			$client->services = $services;
			$data["success"] = true;
			$data["client"] = $client;
		}
		$all_services = DB::table("services")->where("id", '!=', 6)->pluck("name", "id")->toArray();
		$data["services"] = $all_services;
		return Response::json($data, 200, []);
	}

	public function storeClient(Request $request){

		$cre = [
            'name'=>$request->name,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
        ];

        $rules = [
            'name'=>'required',
            'mobile'=>'required',
            'email'=>'required',
        ];

        if(!$request->has('id')){
            $rules['email'] = 'required|unique:users';
        }

        $validator = Validator::make($cre,$rules);

	    if($validator->passes()){

	    	$services = $request->services;

			if($request->has('id')){
				$client_id = $request->id;
				DB::table("clients")->where("id", $client_id)->update([
					"name" => $request->name, 
					"client_name" => $request->client_name, 
					"email" => $request->email, 
					"mobile" => $request->mobile, 
					"gst" => $request->gst, 
					"address" => $request->address,
					"max_users" => $request->max_users,
					"max_logins" => $request->max_logins,
				]);
			} else {
				$client_id = DB::table("clients")->insertGetId([
					"name" => $request->name, 
					"client_name" => $request->client_name, 
					"email" => $request->email, 
					"mobile" => $request->mobile, 
					"gst" => $request->gst, 
					"address" => $request->address,
					"max_users" => $request->max_users,
					"max_logins" => $request->max_logins,
					"created_at" => date("Y-m-d H:i:s"), 
				]);

				$user  = new User;
				$user->client_id = $client_id;
				$user->name = $request->client_name;
				$user->email = $request->email;
				$user->mobile = $request->mobile;
				$user->address = $request->address;
				$user->priv = $request->priv;
				$user->active = 1;
				$password = User::getRandPassword();
                $user->password = Hash::make($password);
                $user->password_check = $password;
				$user->save();
				$user->perent_user_id = $user->id;
				$user->save();
			}

			$active_services = [];

			foreach ($services as $service) {

				$check = DB::table("client_services")->where("client_id", $client_id)->where("services_id", $service["services_id"])->first();

				if($check){
					$id = $check->id;
					DB::table("client_services")->where("id", $id)->update(["status" => 1]);
				} else {
					$id = DB::table("client_services")->insertGetId([
						"client_id" => $client_id,
						"services_id" => $service["services_id"],
						"status" => 1,
					]);
				}

				$active_services[] = $id;

				$this->updateServiceRate($client_id, $service, $id);

			}

			DB::table("client_services")->whereNotIn("id", $active_services)->where("client_id", $client_id)
			->update([
				"status" => 0,
			]);

            $data['success'] = true;
            $data['message'] = "Successfully store!";
		} else {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }

        return Response::json($data, 200, []);
	}

	private function updateServiceRate($client_id, $service, $id){
		if($service["services_id"] == 1){ //Sittinng

			$check_rate = DB::table("sitting_rate_list")->where("client_id", $client_id)->first();
			if($check_rate){
				DB::table("sitting_rate_list")->where("client_id", $client_id)->update([
					"adult_rate" => $service["rate_list"]["adult_rate"],
					"adult_rate_sec" => $service["rate_list"]["adult_rate_sec"],
					"child_rate" => $service["rate_list"]["child_rate"],
					"child_rate_sec" => $service["rate_list"]["child_rate_sec"],
				]);
			} else {
				DB::table("sitting_rate_list")->insert([
					"client_id" => $client_id,
					"adult_rate" => $service["rate_list"]["adult_rate"],
					"adult_rate_sec" => $service["rate_list"]["adult_rate_sec"],
					"child_rate" => $service["rate_list"]["child_rate"],
					"child_rate_sec" => $service["rate_list"]["child_rate_sec"],
				]);
			}

		}

		if($service["services_id"] == 2){ //Cloakroom
			$check_rate = DB::table("cloakroom_rate_list")->where("client_id", $client_id)->first();
			if($check_rate){
				DB::table("cloakroom_rate_list")->where("client_id", $client_id)->update([
					"first_rate" => $service["rate_list"]["first_rate"],
					"second_rate" => $service["rate_list"]["second_rate"],
				]);
			} else {
				DB::table("cloakroom_rate_list")->insert([
					"client_id" => $client_id,
					"first_rate" => $service["rate_list"]["first_rate"],
					"second_rate" => $service["rate_list"]["second_rate"],
					"type"=>1,
				]);
			}
		}				

		if($service["services_id"] == 4){ //Massage
			$check_rate = DB::table("massage_rate_list")->where("client_id", $client_id)->first();
			if($check_rate){
				DB::table("massage_rate_list")->where("client_id", $client_id)->update([
					"first_rate" => $service["rate_list"]["first_rate"],
					"second_rate" => $service["rate_list"]["second_rate"],
				]);
			} else {
				DB::table("massage_rate_list")->insert([
					"client_id" => $client_id,
					"first_rate" => $service["rate_list"]["first_rate"],
					"second_rate" => $service["rate_list"]["second_rate"],
				]);
			}
		}
						

		if($service["services_id"] == 5){ // Locker
			$check_rate = DB::table("locker_rate_list")->where("client_id", $client_id)->first();
			if($check_rate){
				DB::table("locker_rate_list")->where("client_id", $client_id)->update([
					"first_rate" => $service["rate_list"]["first_rate"],
					"second_rate" => $service["rate_list"]["second_rate"],
				]);
			} else {
				DB::table("locker_rate_list")->insert([
					"client_id" => $client_id,
					"first_rate" => $service["rate_list"]["first_rate"],
					"second_rate" => $service["rate_list"]["second_rate"],
				]);
			}

		}

		if($service["services_id"] == 7){ //Recliners
			$check_rate = DB::table("recliner_rate_list")->where("client_id", $client_id)->first();
			if($check_rate){
				DB::table("recliner_rate_list")->where("client_id", $client_id)->update([
					"first_rate" => $service["rate_list"]["first_rate"],
					"second_rate" => $service["rate_list"]["second_rate"],
				]);
			} else {
				DB::table("recliner_rate_list")->insert([
					"client_id" => $client_id,
					"first_rate" => $service["rate_list"]["first_rate"],
					"second_rate" => $service["rate_list"]["second_rate"],
				]);
			}

		}						

		if($service["services_id"] == 9){ //Scanning

			if($service["rate_list"]["outword1_rate"] > 0){
				$check = DB::table("scanning_rate_list")->where("item_type_id", 1)->where("incoming_type_id", 1)->where("client_id", $client_id)->first();
				if($check){
						DB::table("scanning_rate_list")->where("id", $check->id)->update([
						"rate" => $service["rate_list"]["outword1_rate"],
					]);
				} else {
					DB::table("scanning_rate_list")->insert([
						"client_id" => $client_id,
						"rate" => $service["rate_list"]["outword1_rate"],
						"item_type_id" => 1,
						"incoming_type_id" => 1,						
					]);
				}

			}			

			if($service["rate_list"]["inword1_rate"] > 0){
				$check = DB::table("scanning_rate_list")->where("item_type_id", 1)->where("incoming_type_id", 2)->where("client_id", $client_id)->first();
				if($check){
						DB::table("scanning_rate_list")->where("id", $check->id)->update([
						"rate" => $service["rate_list"]["inword1_rate"],
					]);
				} else {
					DB::table("scanning_rate_list")->insert([
						"client_id" => $client_id,
						"rate" => $service["rate_list"]["inword1_rate"],
						"item_type_id" => 1,
						"incoming_type_id" => 2,
					]);
				}

			}			

			if($service["rate_list"]["outword2_rate"] > 0){
				$check = DB::table("scanning_rate_list")->where("item_type_id", 2)->where("incoming_type_id", 1)->where("client_id", $client_id)->first();
				if($check){
						DB::table("scanning_rate_list")->where("id", $check->id)->update([
						"rate" => $service["rate_list"]["outword2_rate"],
					]);
				} else {
					DB::table("scanning_rate_list")->insert([
						"client_id" => $client_id,
						"rate" => $service["rate_list"]["outword2_rate"],
						"item_type_id" => 2,
						"incoming_type_id" => 1,
					]);
				}

			}			

			if($service["rate_list"]["inword2_rate"] > 0){
				$check = DB::table("scanning_rate_list")->where("item_type_id", 2)->where("incoming_type_id", 2)->where("client_id", $client_id)->first();
				if($check){
						DB::table("scanning_rate_list")->where("id", $check->id)->update([
						"rate" => $service["rate_list"]["inword2_rate"],
					]);
				} else {
					DB::table("scanning_rate_list")->insert([
						"client_id" => $client_id,
						"rate" => $service["rate_list"]["inword2_rate"],
						"item_type_id" => 2,
						"incoming_type_id" => 2,
					]);
				}

			}
		}
		return;
	}

}