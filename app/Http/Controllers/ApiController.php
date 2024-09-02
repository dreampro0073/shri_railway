<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB,DateTime;

use App\Models\User;
use App\Models\Canteen;
use App\Models\Item;
use App\Models\CanteenItem;
use App\Models\DailyEntry;


class ApiController extends Controller {

    public function login(Request $request){
        $cre = ["email"=>$request->input("email"),"password"=>$request->input("password")];
        $rules = ["email"=>"required","password"=>"required"];
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

                    $user->can_add_canteen = false;
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
       
        $user = Auth::user();

        $prives = [1,2,3];

        if(in_array($user->priv,$prives)){
            $canteens = $canteens->where('id',$user->client_id);
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
        $canteen_id = $request->canteen_id;
        $users = DB::table('users')->select('id','name','email','mobile','priv')->where("priv", '!=', '4');

        if($request->name){
            $users = $users->where('name','LIKE','%'.$request->name.'%');
        }

        if($request->has('canteen_id')){
            $users = $users->where('canteen_id',$canteen_id);
        }

        $users = $users->orderBy('id', 'DESC')->get();
        $data['success'] = true;
        $data['users'] = $users;
        
        return Response::json($data, 200, []);
    }

    public function editUser(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $user = User::select('id','name','mobile','email','canteen_id','priv')->where('canteen_id', $request->canteen_id)->where('id', $request->user_id)->first();
        if($user){
            $user->mobile = $user->mobile*1;
        }

        $data['success'] = true;
        $data['user'] = $user;

        return Response::json($data, 200, []);

    }

    public function storeUser(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $user_id = 1;
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
            $user->canteen_id = $request->canteen_id;    
            $user->added_by = $user_id;    
            
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
        $user = Auth::user();
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
        $user = Auth::user();
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
        $user = Auth::user();
        $client_id = $user->client_id;
        
        $cre = [
            'item_name'=>$request->item_name,
            'item_short_name'=>$request->item_short_name,
            'price'=>$request->price,
            'barcodevalue'=>$request->barcodevalue,
        ];

        $rules = [
            'item_name'=>'required',
            'item_short_name'=>'required',
            'price'=>'required',
            
        ];
        if($request->has('id')){
            $rules['barcodevalue'] = 'required';

        }else{
            $rules['barcodevalue'] = 'required|unique:canteen_items';

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
        $user = Auth::user();
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

        $user = Auth::user();
        $client_id = $user->client_id;

        $canteen_item_id = $request->canteen_item_id;
        $item_stocks = DB::table('canteen_item_stocks')->where('canteen_item_stocks.canteen_item_id',$canteen_item_id)->where('canteen_item_stocks.client_id',$client_id)->orderBy('id','DESC')->get();

        
        $data["success"] = true;
        $data["item_stocks"] = $item_stocks;
        return Response::json($data,200,array());
    }
    public function editCanteenItemStocks(Request $request){
        $user = Auth::user();
        $client_id = $user->client_id;


        $canteen_item_stock_id = $request->canteen_item_stock_id;
        $item_stock = DB::table('canteen_item_stocks')->where('id',$canteen_item_stock_id)->first();

        $data["success"] = true;
        $data["item_stock"] = $item_stock;
        return Response::json($data,200,array());
    }
    public function storeCanteenItemStock(Request $request){
        $user = Auth::user();
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

    public function canteenItemList(Request $request,$canteen_id=0){   

        $user = Auth::user();
        $client_id = $user->client_id;
      
        $canteen_items = DailyEntry::canteenItemList($client_id);

        $data['success'] = true;
        $data['canteen_items'] = $canteen_items;
       
        return Response::json($data,200,array());

    } 

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

    public function dailyEntries(){
        $sidebar = "daily-entries";
        return view('admin.daily_entries.index',compact('sidebar'));
    }

    public function initEntries(Request $request){
        $user = Auth::user();
        $client_id = $user->client_id;
        $page_no = $request->page_no;
        $date = $request->date;
        $max_per_page = 10;

        $entries = DailyEntry::select('daily_entries.*')->where('client_id',$user->client_id);
        if($request->id){
            $entries = $entries->where('daily_entries.id', $request->id);
        }       
        if($request->name){
            $entries = $entries->where('daily_entries.name', 'LIKE', '%'.$request->name.'%');
        }         
        if($request->mobile){
            $entries = $entries->where('daily_entries.mobile', 'LIKE', '%'.$request->mobile.'%');
        }
        
        $entries = $entries->skip(($page_no-1)*$max_per_page)->take($max_per_page)->orderBy('id','DESC')->where('client_id',$user->client_id)->get();

        foreach ($entries as $key => $entry) {
            $entry->time = date("h:i a,d M",strtotime($entry->created_at));
        }

        $data['success'] = true;
        $data['daily_entries'] = $entries;

        // dd(Auth::user());

        $canteen_items = DB::table('canteen_items')->select('id as canteen_item_id','price','item_name','barcodevalue')->where("stock", '>', 0)->where('client_id',$client_id)->get();

        foreach ($canteen_items as $key => $canteen_item) {
            $canteen_item->quantity  = 1;
            $canteen_item->paid_amount = $canteen_item->price * $canteen_item->quantity; 
        }

        $data["canteen_items"] = $canteen_items;

        return Response::json($data, 200, []);
    }
    
    public function editEntry(Request $request){
        $user = Auth::user();
        $client_id = $user->client_id;
        $s_entry = DailyEntry::where('id', $request->entry_id)->first();

        if($s_entry){
            $s_entry->mobile_no = $s_entry->mobile_no*1;
            $s_entry->paid_amount = $s_entry->paid_amount*1;
            $s_entry->pay_type = $s_entry->pay_type*1;
            if($s_entry->pay_type == 1){
                $s_entry->show_pay_type = 'UPI';
            }
            if($s_entry->pay_type == 2){
                $s_entry->show_pay_type = 'Cash';
            }
            $s_entry->show_date = date("d M Y");
            $s_entry->total_amount = $s_entry->total_amount*1;

            $s_entry->created_time = date("h:i A",strtotime($s_entry->created_at));


            $products = DB::table('daily_entry_items')->select('daily_entry_items.*','canteen_items.id','canteen_items.price','canteen_items.item_name')->leftJoin('canteen_items','canteen_items.id','=','daily_entry_items.canteen_item_id')->where('daily_entry_items.entry_id','=',$s_entry->id)->get();

            $s_entry->products = $products;

            $data['success'] = true;
            $data['s_entry'] = $s_entry;
        }else{
            $data['success'] = false;
            
        }

        return Response::json($data, 200, []);

    }
    public function store(Request $request){
        $user = Auth::user();
        $client_id = $user->client_id;

        $cre = [
            // 'name'=>$request->name,
        ];
        $rules = [
            // 'name'=>'required',
        ];

        $validator = Validator::make($cre,$rules);
        $date = DailyEntry::getPDate();

        if($validator->passes()){
            $unique_id = strtotime("now");
            $ins_data = [
                'unique_id' => strtotime("now"),
                'client_id' => $user->client_id,
                'added_by' => $user->id,
                'name' => $request->has('name')?$request->name:Auth::user()->name,
                'mobile' => $request->has('mobile')?$request->mobile:null,
                'unique_id' => $unique_id,
                'total_amount' =>$request->total_amount,
                'pay_type' =>$request->pay_type,
                'date' => date('Y-m-d'),
                'check_in' => date("H:i:s"),
                'created_at' => date("Y-m-d H:i:s"),
            ];


            $entry_id = DB::table('daily_entries')->insertGetId($ins_data);

            $items = $request->products;
            $final_items = [];
            foreach ($items as $key => $item) {
                
                $canteen_item_id = $item['canteen_item_id'];
                $filtered_array = array_filter($final_items, function ($obj) use ($canteen_item_id) {
                    return $obj['canteen_item_id'] == $canteen_item_id;
                });

                $found_key = array_search($canteen_item_id, array_column($final_items, 'canteen_item_id'));

                
                if(!$filtered_array){
                    $item['paid_amount'] = $item['price'];
                    array_push($final_items, $item);
                }else{
                    $f_ar = $final_items[$found_key];
                    array_splice($final_items,$found_key,1);
                    $f_ar['paid_amount'] = $f_ar['paid_amount'] + $item['price'];
                    $f_ar['quantity'] = $f_ar['quantity'] + $item['quantity'];

                    array_push($final_items,$f_ar);
                }
            }

            
            foreach ($final_items as $key => $item) {
                if($item['quantity'] !=0){
                    DB::table('daily_entry_items')->insert([
                        'canteen_item_id' => $item['canteen_item_id'],
                        'entry_id' => $entry_id,
                        'paid_amount' => $item['paid_amount'],
                        'quantity' => $item['quantity'],
                        'created_at' => date("Y-m-d H:i:s"),
                    ]);

                    $check = DB::table('canteen_items')->where('id',$item['canteen_item_id'])->first();
                    $avil_stock = $check->stock;
                    DB::table('canteen_items')->where('id',$item['canteen_item_id'])->update([
                        'stock' => $avil_stock - $item['quantity'],
                    ]);
                }       
            }


            $data['success'] = true;
            $data['unique_id'] = $unique_id;
            $data['billing_date'] = date("M d Y h:i:sA");
            $data['entry_id'] = $entry_id;
            $data['message'] = "Item's detail is stored successfully!";

        } else {
            $message = $validator->errors()->first();
            $data['success'] = false;
            $data['message'] = $message;
        }

        return Response::json($data, 200, []);
    }

    public function printBill($entry_id=0){
        $user = Auth::user();
        $client_id = $user->client_id;
        $s_entry = DailyEntry::where('id', $entry_id)->first();  

        $s_entry->mobile_no = $s_entry->mobile_no*1;
        $s_entry->paid_amount = $s_entry->paid_amount*1;
        $s_entry->pay_type = $s_entry->pay_type*1;
        if($s_entry->pay_type == 1){
            $s_entry->show_pay_type = 'Cash';
        }
        if($s_entry->pay_type == 2){
            $s_entry->show_pay_type = 'UPI';
        }
        $s_entry->show_date = date("d M Y");
        $s_entry->total_amount = $s_entry->total_amount*1;

        $s_entry->created_time = date("h:i A",strtotime($s_entry->created_at));

        $products = DB::table('daily_entry_items')->select('daily_entry_items.*','canteen_items.id','canteen_items.price','canteen_items.item_name')->leftJoin('canteen_items','canteen_items.id','=','daily_entry_items.canteen_item_id')->where('daily_entry_items.entry_id','=',$s_entry->id)->get();
        
        $s_entry->total_quantity = DB::table('daily_entry_items')->where('daily_entry_items.entry_id','=',$s_entry->id)->sum('quantity');

        $s_entry->products = $products;
        $print_data = $s_entry;
        return view('admin.canteens.daily_entries.print_entry',compact('print_data'));
    }
}
