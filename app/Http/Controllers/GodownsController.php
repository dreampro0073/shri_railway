<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\GodownStocks;

class GodownsController extends Controller {
    public function setGid($value=''){
        DB::table('canteen_items')->whereIn('client_id',[2,3])->where('godown_id',0)->update(['godown_id'=>1]);
    }
	public function index(Request $request){
		$sidebar = 'godown_stocks';
        $subsidebar = 'godown_stocks';
        return view('admin.canteens.godown_stocks.index',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }
    public function history(Request $request,$g_stock_id=0){
        $sidebar = 'godown_stocks';
        $subsidebar = 'godown_stocks';
        return view('admin.canteens.godown_stocks.g_stock_history',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
            'g_stock_id'=>$g_stock_id,
        ]);
    }

    public function init(Request $request){
        $g_entries = GodownStocks::select("godown_stocks.*","canteen_items.item_name")->leftJoin("canteen_items","canteen_items.barcodevalue","=","godown_stocks.barcodevalue")->orderBy("godown_stocks.id","DESC");

        if($request->has('barcodevalue')){
            $g_entries = $g_entries->where('godown_stocks.barcodevalue',$request->barcodevalue);
        }
        if($request->has('item_name')){
            $g_entries = $g_entries->where('canteen_items.item_name','LIKE','%',$request->item_name.'%');
        }

        $g_entries = $g_entries->where('godown_stocks.godown_id','=',Auth::user()->godown_id)->get();

        $canteen_items = DB::table('canteen_items')->select('barcodevalue','item_name')->where('godown_id',Auth::user()->godown_id)->distinct('barcodevalue')->get();

        foreach ($canteen_items as $key => $canteen_item) {
            $canteen_item->item_name = $canteen_item->barcodevalue." - ".$canteen_item->item_name;
        }

        
        $data['success'] = true;
        $data['g_entries'] = $g_entries;   
        $data['canteen_items'] = $canteen_items;   
        return Response::json($data,200,[]);
    }

    public function edit(Request $request){
        $g_id = $request->g_id;
        $g_entry = GodownStocks::find($g_id);
        if($g_entry){
            $g_entry->date = date("d-m-Y",strtotime($g_entry->date));
        }
        $data['success'] = true;
        $data['g_entry'] = $g_entry;   
        return Response::json($data,200,[]);
    }

    public function store(Request $request){

        $cre = [
            "barcodevalue"=>$request->barcodevalue,
            "date"=>$request->date,
            "stock"=>$request->stock,
        ];
        $rules = [
            "barcodevalue"=>"required",
            "date"=>"required",
            "stock"=>"required"
        ];
        $validator = Validator::make($cre,$rules);

        if($validator->passes()){
            $g_entry = GodownStocks::where('barcodevalue',$request->barcodevalue)->first();

            $data['message'] = "Stock successfully updated";


            if(!$g_entry){
                $g_entry = new GodownStocks;
                $data['message'] = "Stock successfully added";

            }
            $total_stock = 0;
            if($g_entry){
                $total_stock = $g_entry->stock;
            }
            $total_stock += $request->stock;


            $g_entry->date = $request->has('date')?date("Y-m-d",strtotime($request->date)):date("Y-m-d");
            $g_entry->barcodevalue = $request->barcodevalue;
            $g_entry->stock = $total_stock;
            $g_entry->org_id = Auth::user()->org_id;
            $g_entry->godown_id = Auth::user()->godown_id;
            $g_entry->save();


            DB::table('godown_stock_history')->insert([
                'godown_stock_id' => $g_entry->id,
                'stock' => $request->stock,
                'date' => $g_entry->date,
                'godown_id' => $g_entry->godown_id,
            ]);

            $data['success'] = true;

        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }
        return Response::json($data,200,array());    
    }

    public function initHistory(Request $request,$g_stock_id){
        $stock_history = DB::table('godown_stock_history');

        if($request->from_date){
            $stock_history = $stock_histor->where('date','=',date("Y-m-d",strtotime($request->from_date)));
        }

        $stock_history = $stock_history->where('godown_stock_id',$g_stock_id)->get();
        
        $data['success'] = true;
        $data['stock_history'] = $stock_history;    
        return Response::json($data,200,[]);
    }

}


