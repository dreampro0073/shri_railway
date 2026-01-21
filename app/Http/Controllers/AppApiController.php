<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB,DateTime;

use App\Models\User;
use App\Models\AppCanteen;
use App\Models\AppItem;
use App\Models\AppCanteenItem;
use App\Models\AppDailyEntry;
use App\Models\CanteenItem;


class AppApiController extends Controller {
        public function getApiVersion(Request $request,$app_version){
        
        // $platform = (Input::get("type") == "ios")?"ios":"android";

        // $version = ($platform == "ios") ? 55 : 55; //ios , android
        
        // if($app_version >= $version){
        //     $data['success'] = true;
        // } else {
            
        //     $data['success'] = false;

        //     if($platform == "ios"){
        //         $data['url'] = "https://apps.apple.com/us/app/bbfs-dugout/id1502992022";
        //         $data['force_login'] = true;
        //     } else {
        //         $data['url'] = "https://play.google.com/store/apps/details?id=com.bbfs.bbfs_coach&hl=en";
        //         $data['force_login'] = true;
        //     }
            
        // }

        $data['success'] = true;
        $data['url'] = "https://play.google.com/store/apps/details?id=com.bbfs.bbfs_coach&hl=en";
        $data['force_login'] = true;

        return Response::json($data,200,array());
    }
	public function login(Request $request){

		$cre = ["email"=>$request->input("email"),"password"=>$request->input("password")];
        $rules = ["email"=>"required","password"=>"required"];
        $validator = Validator::make($cre,$rules);

		if($validator->passes()){

		 	if(Auth::attempt($cre)){
                $user = Auth::user();
                if($user->active == 1){
                    $data["success"] = true;

                    if(!$user->api_token){
                        $user->api_token = Hash::make($user->id.strtotime("now"));
                        $user->save();
                    }
                    $data["user"] = $user;
                    $data["apiToken"] = $user->api_token;
                    $data["message"] = "User Login";
                }else{
                    $data["success"] = false;
                    $data["message"] = "Account is not active";
                }
		 	} else {
		 		$data["success"] = false;
		 		$data["message"] = "Invalid Username and Password";
		 	}
		} else {
		 	$data["success"] = false;
		 	$data["message"] = $validator->errors()->first();
		}
		return Response::json($data,200,array());
	}
    public function mLogin(Request $request){
        
        $cre = ["mobile"=>$request->input("mobile"),"password"=>$request->input("password")];
        $rules = ["mobile"=>"required","password"=>"required"];
        $validator = Validator::make($cre,$rules);

        if($validator->passes()){
            if(Auth::attempt($cre)){
                $user = Auth::user();
                if($user->active == 0){
                    $data["success"] = true;

                    if(!$user->api_token){
                        $user->api_token = md5($user->id.strtotime("now"));
                        $user->save();
                    }
                    $data["user"] = $user;
                    $data["message"] = "User Login";
                }else{
                    $data["success"] = false;
                    $data["message"] = "Account is not active";
                }
            } else {
                $data["success"] = false;
                $data["message"] = "Invalid Username and Password";
            }
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }
        return Response::json($data,200,array());
    }

	public function changePassword(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $user_id = $user->id;
		
        $cre = ["old_password"=>$request->old_password,"password"=>$request->password,"confirm_password"=>$request->confirm_password];
        $rules = ["old_password"=>'required',"password"=>"required|min:8","confirm_password"=>"required|min:8|same:password"];

        $validator = Validator::make($cre,$rules);
        
        if ($validator->passes()) {

            if(Hash::check($request->old_password, $user->password )) {
                $password = Hash::make($request->password);
                DB::table('users')->where("id", $user_id)->update([
                    "password" => $password,
                    "password_check" => $request->password
                ]);
                
                $data["success"] = true;
                $data["message"] ="Password is updated successfully!";
            } else {
                $data["success"] = false;
                $data["message"] = "The old password you have entered is incorrect.";
            } 

		}else{
		 	$data["success"] = false;
		 	$data["message"] = $validator->errors()->first();
		}

		return Response::json($data,200,array());

	}


    public function initCanteens(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $canteens = DB::table('canteens')->orderBy('id', 'DESC');

        $prives = [2,3];

        if(in_array($user->priv,$prives)){
            $canteens = $canteens->where('id',$user->canteen_id);
        }
        $canteens = $canteens->get();
        $data["success"] = true;
        $data["canteens"] = $canteens;
        return Response::json($data,200,array());
    }

    public function editCanteen(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $canteen = DB::table('canteens')->where('id',$request->canteen_id)->first();
        $data["success"] = true;
        $data["canteen"] = $canteen;
        return Response::json($data,200,array());
    }

    public function storeCanteen(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $canteen_id = $request->id;

        $cre = [
            'name'=>$request->name,
            'city'=>$request->city,
            'address'=>$request->address,
        ];

        $rules = [
            'name'=>'required',
            'city'=>'required',
            'address'=>'required',
        ];

        $validator = Validator::make($cre,$rules);
        if($validator->passes()){
            if($canteen_id == 0){
                $canteen = new Canteen;
                $canteen->status = 0;
                $data['message'] = "Canteen is Added successfully";

            } else {
                $canteen = Canteen::find($request->id);
                $data['message'] = "Canteen is updated successfully"; 
            }   

            $canteen->name = $request->name;
            $canteen->city = $request->city;
            $canteen->address = $request->address;
            $canteen->save();
            
            $data['success'] = true;

        } else {
            $message = $validator->errors()->first();
            $data['success'] = false;
            $data['message'] = $message;
            
        }
        return Response::json($data, 200, []);
    }
    public function initUsers(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        $users = DB::table('users')->select('id','name','email','mobile','priv');

        if($request->name){
            $users = $users->where('name','LIKE','%'.$request->name.'%');
        }

        // $users = $users->where('client_id',$client_id);

        $users = $users->where('client_id',$user->client_id)->orderBy('id', 'DESC')->get();
        $data['success'] = true;
        $data['users'] = $users;
        
        return Response::json($data, 200, []);
    }

    public function editUser(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $user = User::select('id','name','mobile','email','client_id','priv')->where('client_id', $user->client_id)->where('id', $request->user_id)->first();

        if($user){
            $user->mobile = $user->mobile*1;
        }

        $data['success'] = true;
        $data['user'] = $user;

        return Response::json($data, 200, []);

    }

    public function storeUser(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $user_id = $user->id;   
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
        
        if($request->id == 0){
            $rules['email'] = 'required|unique:users';
       
            $cre['password'] = $request->password;
            $cre['confirm_password'] = $request->confirm_password;

            $rules['password'] = 'required';
            $rules['confirm_password'] = 'required|same:password';
            
        }  

        $validator = Validator::make($cre,$rules);

        if($validator->passes()){
            
            if($request->id != 0){
                $user = User::find($request->id);
                $data['message'] = 'User is updated successfully';

            } else {
                $user = new User;
                $user->password_check = $request->password;
                $user->password = Hash::make($request->password);
                $user->priv = $request->priv;
                $data['message'] = 'User is added successfully';

            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;    
            $user->priv = $request->priv;    
            $user->client_id = $user->client_id;    
            // $user->added_by = $user_id;    
            
            $user->save();
            $data['success'] = true;

        } else {
            $data['success'] = false;
            $message = $validator->errors()->first();
            $data['message'] = $message;
        }

        return Response::json($data, 200, []);

    } 



    public function initCanteenItems(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $client_id = $user->client_id;

        $canteen_items = DB::table('canteen_items');
        if($request->has('item_name')){
            $canteen_items = $canteen_items->where('item_name','LIKE','%'.$request->item_name.'%');
        }
        // if($request->has('item_name')){
        //     $canteen_items = $canteen_items->where('item_short_name','LIKE','%'.$request->item_name.'%');
        // }

         if($request->has('barcodevalue_search')){
            $canteen_items = $canteen_items->where('barcodevalue','LIKE','%'.$request->barcodevalue_search.'%');
        }

        $canteen_items = $canteen_items->where('client_id',$client_id)->orderBy('id','DESC')->get();

        $data["success"] = true;
        $data["canteen_items"] = $canteen_items;
        return Response::json($data,200,array());
    }
    public function editCanteenItem(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $client_id = $user->client_id;

        $canteen_item = CanteenItem::find($request->canteen_item_id);

        if($canteen_item){
            $canteen_item->price = $canteen_item->price*1;
        }
        $data["success"] = true;
        $data["canteen_item"] = $canteen_item;

        return Response::json($data,200,array());
    }
    public function storeCanteenItem(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $client_id = $user->client_id;
        
        $cre = [
            'item_name'=>$request->item_name,
            'item_short_name'=>$request->item_short_name,
            'price'=>$request->price,
            // 'barcodevalue'=>$request->barcodevalue,
        ];

        $rules = [
            'item_name'=>'required',
            'item_short_name'=>'required',
            'price'=>'required',
            
        ];
        if($request->has('id')){
            $rules['barcodevalue'] = 'required';

        }else{
            $c_item = CanteenItem::where('barcodevalue', $request->barcodevalue)->where('client_id', $client_id)->first();
            if($c_item){
                $rules['barcodevalue'] = 'required|unique:canteen_items';
            }else{
                $rules['barcodevalue'] = 'required';
            }
        }

        $validator = Validator::make($cre,$rules);

        if($validator->passes()){

            $canteen_item = CanteenItem::find($request->id);
            $data['message'] = 'This item is updated successfully to the canteen';
            if(!$canteen_item){
                $canteen_item = new CanteenItem;
                $data['message'] = 'This item is added successfully to the canteen';
            }
            $canteen_item->item_name = $request->item_name; 
            $canteen_item->item_short_name = $request->item_short_name; 
            $canteen_item->price = $request->price; 
            $canteen_item->added_by = $user->id; 
            $canteen_item->client_id = $user->client_id; 
            // $canteen_item->godwon_id = $user->godwon_id; 
            $canteen_item->barcodevalue = $request->barcodevalue; 

            $canteen_item->save();
            $data['success'] = true;
            
        } else {
            $message = $validator->errors()->first();
            $data['success'] = false;
            $data['message'] = $message;
            
        }
        return Response::json($data, 200, []);
    }
    public function initCanteenItemsDrop(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $client_id = $user->client_id;

        $canteen_items = DB::table('canteen_items')->select('id as canteen_item_id','price','item_name','barcodevalue')->where('status',0)->where('stock','>',0)->where('client_id',$client_id)->get();

        foreach ($canteen_items as $key => $canteen_item) {
            $canteen_item->quantity  = 1;
            $canteen_item->paid_amount = $canteen_item->price * $canteen_item->quantity; 
        }

        $data["success"] = true;
        $data["canteen_items"] = $canteen_items;
        return Response::json($data,200,array());
    }

    public function initCanteenItemStocks (Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $client_id = $user->client_id;

        $canteen_item_id = $request->canteen_item_id;
        $item_stocks = DB::table('canteen_item_stocks')->where('canteen_item_stocks.canteen_item_id',$canteen_item_id)->where('canteen_item_stocks.client_id',$client_id)->orderBy('id','DESC')->get();

        
        $data["success"] = true;
        $data["item_stocks"] = $item_stocks;
        return Response::json($data,200,array());
    }
    public function editCanteenItemStocks(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $client_id = $user->client_id;


        $canteen_item_stock_id = $request->canteen_item_stock_id;
        $item_stock = DB::table('canteen_item_stocks')->where('id',$canteen_item_stock_id)->first();

        $data["success"] = true;
        $data["item_stock"] = $item_stock;
        return Response::json($data,200,array());
    }
    public function storeCanteenItemStock(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $client_id = $user->client_id;
        
        $cre = [
            'canteen_item_id'=>$request->canteen_item_id,
            'stock'=>$request->stock,
        ];

        $rules = [
            'canteen_item_id'=>'required',
            'stock'=>'required',
        ];

        $validator = Validator::make($cre,$rules);
        if($validator->passes()){

            $ins_data = [
                'client_id' => $client_id,
                'canteen_item_id' => $request->canteen_item_id,
                'stock' => $request->stock,
                'added_by' => $user->id,

            ];
            $canteen_item = CanteenItem::find($request->canteen_item_id);
            $current_item_stock = (isset($canteen_item->stock))?$canteen_item->stock:0;

            if($request->id){  

                $canteen_item_stock = DB::table('canteen_item_stocks')->where('id',$request->id)->first();
                $added_stock = $canteen_item->stock  - $canteen_item_stock->stock;
            
                DB::table('canteen_item_stocks')->where('id',$request->id)->update($ins_data);
                $data['message'] = "Successfully Updated";
            }else{
                $ins_data['date'] = date("Y-m-d");
                $ins_data['created_at'] = date("Y-m-d H:i:s");
                DB::table('canteen_item_stocks')->insert($ins_data);
                $data['message'] = "Successfully Added";

                $added_stock= $current_item_stock;
            }
            $added_stock = $added_stock+$request->stock;
            $canteen_item->stock = $added_stock;
            $canteen_item->save();
            $data['success'] = true;

            
        } else {
            $message = $validator->errors()->first();
            $data['success'] = false;
            $data['message'] = $message;
            
        }
        return Response::json($data, 200, []);
    }

    // public function initCanteenItems(Request $request){
    //     $user = User::AuthenticateUser($request->header("apiToken"));
    //     $canteen_id = $request->canteen_id;

    //     $canteen_items = DB::table('canteen_items')->where('canteen_id',$canteen_id)->get();

    //     $data["success"] = true;
    //     $data["canteen_items"] = $canteen_items;
    //     return Response::json($data,200,array());
    // }
    // public function editCanteenItem(Request $request){
    //     $user = User::AuthenticateUser($request->header("apiToken"));
    //     $canteen_item = CanteenItem::find($request->canteen_item_id);
    //     if($canteen_item){
    //         $canteen_item->price = $canteen_item->price*1;
    //     }
    //     $data["success"] = true;
    //     $data["canteen_item"] = $canteen_item;

    //     return Response::json($data,200,array());
    // }
    // public function storeCanteenItem(Request $request){
    //     $user = User::AuthenticateUser($request->header("apiToken"));
        
    //     $cre = [
    //         'item_name'=>$request->item_name,
    //         'item_short_name'=>$request->item_short_name,
    //         'price'=>$request->price,
    //         'canteen_id'=>$request->canteen_id,
    //     ];

    //     $rules = [
    //         'item_name'=>'required',
    //         'item_short_name'=>'required',
    //         'price'=>'required',
    //         'canteen_id'=>'required',
    //     ];
    //     $validator = Validator::make($cre,$rules);
    //     if($validator->passes()){

    //         $canteen_item = CanteenItem::find($request->id);
    //         $data['message'] = 'This item is updated successfully to the canteen';
    //         if(!$canteen_item){
    //             $canteen_item = new CanteenItem;
    //             $data['message'] = 'This item is added successfully to the canteen';
    //         }
    //         $canteen_item->item_name = $request->item_name; 
    //         $canteen_item->item_short_name = $request->item_short_name; 
    //         $canteen_item->price = $request->price; 
    //         $canteen_item->canteen_id = $request->canteen_id; 
    //         $canteen_item->added_by = $user->id; 

            
    //         $canteen_item->save();
    //         $data['success'] = true;
            
    //     } else {
    //         $message = $validator->errors()->first();
    //         $data['success'] = false;
    //         $data['message'] = $message;
            
    //     }
    //     return Response::json($data, 200, []);
    // }
    // public function initCanteenItemsDrop(Request $request){
    //     $user = User::AuthenticateUser($request->header("apiToken"));
    //     $canteen_id = $request->canteen_id;

    //     $canteen_items = DB::table('canteen_items')->select('id as canteen_item_id','price','item_name')->where('canteen_id',$canteen_id)->get();

    //     foreach ($canteen_items as $key => $canteen_item) {
    //         $canteen_item->quantity  = 1;
    //         $canteen_item->paid_amount = $canteen_item->price * $canteen_item->quantity; 
    //     }

    //     $data["success"] = true;
    //     $data["canteen_items"] = $canteen_items;
    //     return Response::json($data,200,array());
    // }

    // public function initCanteenItemStocks (Request $request){
    //     $user = User::AuthenticateUser($request->header("apiToken"));

    //     $canteen_id = $request->canteen_id;
    //     $canteen_item_id = $request->canteen_item_id;
    //     $item_stocks = DB::table('canteen_item_stocks')->where('canteen_item_stocks.canteen_item_id',$canteen_item_id)->where('canteen_item_stocks.canteen_id',$canteen_id)->get();
    
    //     foreach($item_stocks as $item_stock){
    //         // $item->show_date = date("d M y",strtotime($item_stock->date));
    //     }
        
    //     $data["success"] = true;
    //     $data["item_stocks"] = $item_stocks;
    //     return Response::json($data,200,array());
    // }
    // public function editCanteenItemStocks(Request $request){
    //     $user = User::AuthenticateUser($request->header("apiToken"));
    //     $canteen_id = $request->canteen_id;
    //     $canteen_item_stock_id = $request->canteen_item_stock_id;
    //     $item_stock = DB::table('canteen_item_stocks')->where('id',$canteen_item_stock_id)->first();

    //     $data["success"] = true;
    //     $data["item_stock"] = $item_stock;
    //     return Response::json($data,200,array());
    // }
    // public function storeCanteenItemStock(Request $request){
    //     $user = User::AuthenticateUser($request->header("apiToken"));
        
    //     $cre = [
    //         'canteen_item_id'=>$request->canteen_item_id,
    //         'canteen_id'=>$request->canteen_id,
    //         'stock'=>$request->stock,
    //     ];

    //     $rules = [
    //         'canteen_item_id'=>'required',
    //         'canteen_id'=>'required',
    //         'stock'=>'required',
    //     ];

    //     $validator = Validator::make($cre,$rules);
    //     if($validator->passes()){

    //         $ins_data = [
    //             'canteen_id' => $request->canteen_id,
    //             'canteen_item_id' => $request->canteen_item_id,
    //             'stock' => $request->stock,
    //             'added_by' => $user->id,

    //         ];
    //         $canteen_item = CanteenItem::find($request->canteen_item_id);
    //         $current_item_stock = ($canteen_item->stock)?$canteen_item->stock:0;

    //         if($request->id){
                

    //             $canteen_item_stock = DB::table('canteen_item_stocks')->where('id',$request->id)->first();
    //             $added_stock = $canteen_item->stock  - $canteen_item_stock->stock;
            
    //             DB::table('canteen_item_stocks')->where('id',$request->id)->update($ins_data);
    //             $data['message'] = "Successfully Updated";
    //         }else{
    //             $ins_data['date'] = date("Y-m-d");
    //             $ins_data['created_at'] = date("Y-m-d H:i:s");
    //             DB::table('canteen_item_stocks')->insert($ins_data);
    //             $data['message'] = "Successfully Added";

    //             $added_stock= $current_item_stock;
    //         }
    //         $added_stock = $added_stock+$request->stock;
    //         $canteen_item->stock = $added_stock;
    //         $canteen_item->save();
    //         $data['success'] = true;

            
    //     } else {
    //         $message = $validator->errors()->first();
    //         $data['success'] = false;
    //         $data['message'] = $message;
            
    //     }
    //     return Response::json($data, 200, []);
    // }


    
   

    public function reasons(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $reasons = [
            array("value"=>"1","label"=>"I have another account"),
            array("value"=>"2","label"=>"Security issue"),
        ];

        $data['success'] = true;
        $data['reasons'] = $reasons;
       
        return Response::json($data,200,array());

    }
    public function deleteMyAccount(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        if($user){
            $user->active = 1;
            $user->save();

            $data['success'] = true;
            $data['message'] = 'Your request for delete account is successfully submitted. your account is under deletion process. It will be take upto 24 hours.';
        }else{
            $data['success'] = false;
        }  
        return Response::json($data,200,array());
    }
}
