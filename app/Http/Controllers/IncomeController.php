<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Income;
use App\Models\Expense;
use App\Models\IncomeEntry;
use App\Models\Sitting,App\Models\CloakRoom, App\Models\Canteen,App\Models\Massage,App\Models\Locker,App\Models\Entry;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;

class IncomeController extends Controller {

	public function index(Request $request){
		$sidebar = 'acc';
        $subsidebar = 'income';
        return view('admin.incomes.index',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function init(Request $request){
        $incomes = Income::getIncomes($request);
        $income_types = Expense::incomeTypes();
        $data['success'] = true;
        $data['income_types']= Expense::incomeTypes();
        $data['clients'] = Sitting::getBranches();
        $data['incomes'] = $incomes;
        
        return Response::json($data,200,[]);
    }

    public function editForm($income_id = 0){
        $sidebar = 'acc';
        $subsidebar = 'income';
        return view('admin.incomes.add',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
            'income_id'=>$income_id,
        ]);
    }

    public function edit(Request $request){



        // $date = $request->date?date("Y-m-d",strtotime($request->date)):date("Y-m-d");
        $date = $request->date?date("Y-m-d",strtotime($request->date)):"2024-09-25";
        $client_id = $request->client_id ? $request->client_id : Auth::user()->client_id;

        $service_ids = Entry::getServiceIds($client_id);

        $check = Income::where('date',$date)->where('client_id',$client_id)->first();

        $c_services = [];
        $total_amount = 0;
        if(in_array(1,$service_ids)){
            $sitting_data = Sitting::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>1,
                "source" => "Sitting",
                "cash_amount"=>$sitting_data['total_shift_upi'],
                "upi_amount"=>$sitting_data['total_shift_cash'],
                "total_amount"=>$sitting_data['total_collection'],
            ];

            $total_amount += $sitting_data['total_collection'];
        }

        if(in_array(2,$service_ids)){
            $cloak_data = CloakRoom::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>2,
                "source" => "cloakroom",
                "cash_amount"=>$cloak_data['total_shift_upi'],
                "upi_amount"=>$cloak_data['total_shift_cash'],
                "total_amount"=>$cloak_data['total_collection'],
            ];

            $total_amount += $cloak_data['total_collection'];
        }

        if(in_array(3,$service_ids)){
            $cant_data = Canteen::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>3,
                "source" => "Canteen",
                "cash_amount"=>$cant_data['total_shift_upi'],
                "upi_amount"=>$cant_data['total_shift_cash'],
                "total_amount"=>$cant_data['total_collection'],
            ];
            $total_amount += $cant_data['total_collection'];
        }
        if(in_array(4,$service_ids)){
            $massage_data = Massage::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>4,
                "source" => "Massage",
                "cash_amount"=>$massage_data['total_shift_upi'],
                "upi_amount"=>$massage_data['total_shift_cash'],
                "total_amount"=>$massage_data['total_collection'],
            ];
            $total_amount += $massage_data['total_collection'];
        }
        if(in_array(5,$service_ids)){
            $locker_data = Locker::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>5,
                "source" => "Locker",
                "cash_amount"=>$locker_data['total_shift_upi'],
                "upi_amount"=>$locker_data['total_shift_cash'],
                "total_amount"=>$locker_data['total_collection'],
            ];
            $total_amount += $locker_data['total_collection'];
        }
        if(in_array(7,$service_ids)){
            if($check){
                $other_data = DB::table("income_entries")->where("income_id", $check->id)->where("service_id", 7)->first();

                $c_services[] = [
                    "service_id" =>7,
                    "source" => "Others",
                    "cash_amount"=>$other_data->upi_amount ? $other_data->upi_amount : 0,
                    "upi_amount"=>$other_data->cash_amount ? $other_data->cash_amount : 0,
                    "total_amount"=>$other_data->total_amount ? $other_data->total_amount : 0,
                ];
            }else{
                $c_services[] = [
                    "service_id" =>7,
                    "source" => "Others",
                    "cash_amount"=>0,
                    "upi_amount"=>0,
                    "total_amount"=>0,
                ];
            }
            
        }

        // $data['income_types']= Expense::incomeTypes();
        // $date = $request->date?$request->date : date("Y-m-d");
        // $client_id = $request->client_id ? $request->client_id : Auth::user()->client_id;
        // $income = DB::table('incomes')->where("date", date('Y-m-d',strtotime($date)))->where("client_id", $client_id)->first();
        // if ($income) {
        //     $income->date = date('d-m-Y',strtotime($income->date));
        //     $multiple_income = DB::table('income_entries')->where('income_id',$income->id)->get();
        //     $income->multiple_income = $multiple_income;
        // }
        // $data['income'] = $income;




        $formData  = new \stdClass;
        $formData->date = date('d-m-Y',strtotime($date));
        $formData->client_id = $client_id;
        $formData->c_services = $c_services;
        // $formDa ta->total_amount = $total_amount;
        

        $data['success'] = true;
        $data['clients'] = Sitting::getBranches();
        $data['formData'] = $formData;
        // $data['c_services'] = $c_services;
        // $data['client_id'] = $client_id;
        // $data['date'] = date('d-m-Y',strtotime($date));

        return Response::json($data,200,[]);
    }    

    // public function store(Request $request){

    //     $cre = [
            
    //     ];
    //     $rules=[
            
    //     ];
    //     $validator = Validator::make($cre,$rules);
    //     if ($validator->passes()) {
    //         $income = Income::find($request->id);
    //         $data['message']= 'Updated successfully';

    //         if(!$income){
    //             $income = new Income;
    //             $data['message']='Added successfully';
    //         }   

    //         $income->date = $request->has('date')?date("Y-m-d",strtotime($request->date)):date("Y-m-d");
    //         $income->total_amount = $request->total_amount;
    //         $income->back_balance = $request->back_balance;
    //         $income->all_total = $request->all_total;
    //         $income->added_by = Auth::id();
    //         $income->client_id = $request->client_id;
    //         $income->save();

    //         if ($request->multiple_income) {
    //             if (sizeof($request->multiple_income)>0) {
    //                 $multiple_income = $request->multiple_income;
    //                 foreach ($multiple_income as $single_income) {
    //                     if (isset($single_income['id'])) {
    //                         $income_entry = IncomeEntry::find($single_income['id']);       
    //                     }else{
    //                         $income_entry = new IncomeEntry;
                            
    //                     }
    //                     $income_entry->from =(isset($single_income['from']))?$single_income['from']:0;
    //                     $income_entry->date =$income->date;
                        
                        

    //                     if (isset($single_income['attachment'])) {
    //                         if ($single_income['attachment'] !=null && $single_income['attachment']) {
    //                             $income_entry->income_file = $single_income['attachment'];
    //                         }else{
    //                             $income_entry->income_file =null;
    //                         }
    //                     }

    //                     $income_entry->amount =(isset($single_income['amount']))?$single_income['amount']:null;
                        
    //                     $income_entry->income_type =(isset($single_income['income_type']))?$single_income['income_type']:0;
    //                     $income_entry->remarks =(isset($single_income['remarks']))?$single_income['remarks']:null;
    //                     $income_entry->income_id = $income->id;
    //                     $income_entry->client_id = $income->client_id;
    //                     $income_entry->save();

    //                     $data['success'] =true;
    //                 }

    //             }else{
    //                 $data['success']=false;
    //                 $data['message'] =$validator->errors()->first();
    //             }
    //         }
    //     }else{
    //         $data['success'] =false;
    //         $data['message']='Please fill details';
    //     }

        

    //     return Response::json($data,200,array());
    // }



    public function summary(Request $request){
        $sidebar = 'acc';
        $subsidebar = 'summary';
        return view('admin.incomes.summary',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function summaryInit(Request $request){
        $to_date = $request->to_date ? date("Y-m-d", strtotime($request->to_date)) : date("Y-m-d");
        $from_date = $request->from_date ? date("Y-m-d", strtotime($request->from_date)) : date("Y-m-d");
        $client_id = $request->client_id ? $request->client_id : Auth::user()->client_id;
        $income_types = Expense::incomeTypes();

        $incomes_sql = DB::table('incomes')->select('incomes.*','clients.client_name')->leftJoin('clients','clients.id','=','incomes.client_id');

        $date_ar = [];
        if($from_date && $to_date){
            $incomes_sql = $incomes_sql->whereBetween('incomes.date',[date("Y-m-d",strtotime($from_date)),date("Y-m-d",strtotime($to_date))]);
        }

        if ($client_id) {
            $incomes_sql = $incomes_sql->where('incomes.client_id','=',$client_id);
        }

        if ($request->income_type) {
            $incomes_sql = $incomes_sql->where('incomes.income_type','=',$request->income_type);
        }
        $total_incomes = $incomes_sql;
        $total_incomes = $total_incomes->sum("all_total");
        $incomes = $incomes_sql->orderBy('incomes.date','DESC')->get();
        $total_cash = 0;
        $total_upi = 0;

        foreach ($incomes as $key => $income) {
            $income->multiple_income = Income::getMultiIncomes($income);
            $income->date = date('d-m-Y',strtotime($income->date));
            $income->show_income_type = (isset($income->income_type))?$income_types[$income->income_type]:'NA';
            $income->total_cash = DB::table('income_entries')->where('income_id',$income->id)->whereIn('income_type',[1,3,5,7])->sum('amount');
            $total_cash  = $total_cash + $income->total_cash;
            $income->total_upi = DB::table('income_entries')->where('income_id',$income->id)->whereIn('income_type',[2,4,6,8])->sum('amount');
            $total_upi  = $total_upi + $income->total_upi;
        }

        $expenses_sql = Expense::select('expenses.*','clients.client_name')->leftJoin('clients','clients.id','=','expenses.client_id');
        
        if($from_date && $to_date){
            $expenses_sql = $expenses_sql->whereBetween('expenses.date',[date("Y-m-d",strtotime($from_date)),date("Y-m-d",strtotime($to_date))]);
        } 
        
        if ($client_id) {
            $expenses_sql = $expenses_sql->where('expenses.client_id','=',$client_id);
        }
        $total_expenses = $expenses_sql;
        $total_expenses = $total_expenses->sum("total_amount");
        $expenses = $expenses_sql->orderBy('expenses.date','DESC')->get();

        $data['success'] = true;
        $data['clients'] = Sitting::getBranches();
        $data['income_types'] = $income_types;
        $data['incomes'] = $incomes;
        $data['expenses'] = $expenses;
        $data['total_expenses'] = $total_expenses;
        $data['total_incomes'] = $total_incomes;

        $data['total_cash'] = $total_cash;
        $data['total_upi'] = $total_upi;

        if($request->export == 1){
            $data["to_date"] = $to_date;
            $data["from_date"] = $from_date;
            $data["branch"] = DB::table('clients')->where('id', $client_id)->first();
            $dompdf = new Dompdf();
            $html = view('admin.incomes.summary_pdf',['data'=>$data]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfContent = $dompdf->output();

            $filename = strtotime("now").".pdf";

            $pdfPath = public_path('temp/'.$filename);
            File::ensureDirectoryExists(public_path('temp'));
            File::put($pdfPath, $pdfContent);

            $data['export_link'] = $filename;
        }
        
        return Response::json($data,200,[]);
    }

}


