<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Income, App\Models\Recliner;
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
        $data['success'] = true;
        $data['income_types']= Expense::incomeTypes();
        $data['clients'] = Sitting::getBranches();
        $data['incomes'] = $incomes;
        
        return Response::json($data,200,[]);
    }

    public function printIncome($income_id=0){
        $check = Income::select('incomes.*','clients.client_name')->leftJoin('clients','clients.id','=','incomes.client_id')->where('incomes.id',$income_id)->first();

        // dd($check);
        if($check){
            $date = date("Y-m-d",strtotime($check->date));
            $client_id = $check->client_id;

            $dompdf = new Dompdf();
            $income = $this->getFormData($date, $client_id);

            // dd($income);

            $html = view('admin.incomes.print_pdf',['income'=>$income,'check'=>$check]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
        }
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

        $date = $request->date?date("Y-m-d",strtotime($request->date)):date("Y-m-d");
        $client_id = $request->client_id ? $request->client_id : Auth::user()->client_id;

        $data['success'] = true;
        $data['clients'] = Sitting::getBranches();
        $data['formData'] = $this->getFormData($date, $client_id);

        return Response::json($data,200,[]);
    } 

    public function getFormData($date, $client_id){
        $check = Income::where('date',$date)->where('client_id',$client_id)->first();
        $income_entries = [];
        if($check){
            $income_entries = DB::table("income_entries")->where("income_id", $check->id)->pluck("remarks", 'service_id')->toArray();
        }

        $service_ids = Entry::getServiceIds($client_id);
        $c_services = [];
        $total_amount = 0;
        if(in_array(1,$service_ids)){
            $sitting_data = Sitting::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>1,
                "source" => "Sitting",
                "upi_amount"=>$sitting_data['total_shift_upi'],
                "cash_amount"=>$sitting_data['total_shift_cash'],
                "total_amount"=>$sitting_data['total_collection'],
                "remarks" => isset($income_entries[1]) ? $income_entries[1] : "",
            ];

            $total_amount += $sitting_data['total_collection'];
        }

        if(in_array(2,$service_ids)){
            $cloak_data = CloakRoom::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>2,
                "source" => "cloakroom",
                "upi_amount"=>$cloak_data['total_shift_upi'],
                "cash_amount"=>$cloak_data['total_shift_cash'],
                "total_amount"=>$cloak_data['total_collection'],
                "remarks" => isset($income_entries[2]) ? $income_entries[2] : "",
            ];

            $total_amount += $cloak_data['total_collection'];
        }

        if(in_array(3,$service_ids)){
            $cant_data = Canteen::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>3,
                "source" => "Canteen",
                "upi_amount"=>$cant_data['total_shift_upi'],
                "cash_amount"=>$cant_data['total_shift_cash'],
                "total_amount"=>$cant_data['total_collection'],
                "remarks" => isset($income_entries[3]) ? $income_entries[3] : "",
            ];
            $total_amount += $cant_data['total_collection'];
        }
        if(in_array(4,$service_ids)){
            $massage_data = Massage::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>4,
                "source" => "Massage",
                "upi_amount"=>$massage_data['total_shift_upi'],
                "cash_amount"=>$massage_data['total_shift_cash'],
                "total_amount"=>$massage_data['total_collection'],
                "remarks" => isset($income_entries[4]) ? $income_entries[4] : "",
            ];
            $total_amount += $massage_data['total_collection'];
        }
        if(in_array(5,$service_ids)){
            $locker_data = Locker::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>5,
                "source" => "Locker",
                "upi_amount"=>$locker_data['total_shift_upi'],
                "cash_amount"=>$locker_data['total_shift_cash'],
                "total_amount"=>$locker_data['total_collection'],
                "remarks" => isset($income_entries[5]) ? $income_entries[5] : "",
            ];
            $total_amount += $locker_data['total_collection'];
        }        
        if(in_array(7,$service_ids)){
            $recliner_data = Recliner::totalShiftData($date,0,$client_id);
            $c_services[] = [
                "service_id" =>7,
                "source" => "Recliners",
                "upi_amount"=>$recliner_data['total_shift_upi'],
                "cash_amount"=>$recliner_data['total_shift_cash'],
                "total_amount"=>$recliner_data['total_collection'],
                "remarks" => isset($income_entries[7]) ? $income_entries[7] : "",
            ];
            $total_amount += $recliner_data['total_collection'];
        }
        if(in_array(6,$service_ids)){
            if($check){
                $other_data = DB::table("income_entries")->where("income_id", $check->id)->where("service_id", 6)->first();

                $c_services[] = [
                    "service_id" =>6,
                    "source" => "Others",
                    "cash_amount"=>$other_data->cash_amount ? $other_data->cash_amount : 0,
                    "upi_amount"=>$other_data->upi_amount ? $other_data->upi_amount : 0,
                    "total_amount"=>$other_data->total_amount ? $other_data->total_amount : 0,
                    "remarks" => isset($income_entries[6]) ? $income_entries[6] : "",
                ];
            }else{
                $c_services[] = [
                    "service_id" =>6,
                    "source" => "Others",
                    "cash_amount"=>0,
                    "upi_amount"=>0,
                    "total_amount"=>0,
                    "remarks" => "",
                ];
            }
            
        }

        $formData  = new \stdClass;
        $formData->date = date('d-m-Y',strtotime($date));
        $formData->client_id = $client_id;
        $formData->c_services = $c_services;
        $formData->back_balance = isset($check) ? $check->back_balance : 0;
        if($check){
            $formData->id = $check->id;
        }



        return $formData;
    
    }

    public function store(Request $request){
        $cre =[];
        $rules =[];
        $validator = Validator::make($cre,$rules);

        if($validator->passes()){
            $income = Income::find($request->id);

            $date = date("Y-m-d",strtotime($request->date));

            if(!$income){
                $income = new Income;
            }

            $income->date = $date;
            $income->client_id = $request->client_id;
            $income->total_amount = $request->total_amount;
            $income->back_balance = $request->back_balance;
            $income->day_total = $request->all_total;
            $income->cash_amount = $request->cash_amount;
            $income->upi_amount = $request->upi_amount;
            $income->added_by = Auth::id();
            
            $income->save();

            if(sizeof($request->c_services) > 0){
                foreach ($request->c_services as $key => $item) {
                    $check = DB::table('income_entries')->where('service_id',$item['service_id'])->where('income_id',$income->id)->first();


                    $ins_data  = [
                        'income_id' => $income->id,
                        'client_id' => $income->client_id,
                        'service_id' => $item['service_id'],
                        'cash_amount' => $item['cash_amount'],
                        'upi_amount' => $item['upi_amount'],
                        'total_amount' => $item['total_amount'],
                        'remarks' => $item['remarks'],
                        'source' => $item['source'],
                        'date' =>$date,
                        
                    ];

                    if($check){
                        DB::table('income_entries')->where('id',$check->id)->update($ins_data);
                    }else{
                        DB::table('income_entries')->insert($ins_data);

                    }
                }
            }


            $data["success"] = true;
            $data["message"] = "Updated Successfully !";
        }else{
            $data['success']=false;
            $data['message'] =$validator->errors()->first();
        }
        return Response::json($data,200,[]);

    } 


    public function summary(Request $request){
        $sidebar = 'acc';
        $subsidebar = 'summary';
        return view('admin.incomes.summary',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function summaryInit(Request $request){
        $date = $request->date ? date("Y-m-d", strtotime($request->date)) : date("Y-m-d");
        $client_id = $request->client_id ? $request->client_id : Auth::user()->client_id;

        $income_sql = DB::table('incomes')->select('incomes.*','clients.client_name')->leftJoin('clients','clients.id','=','incomes.client_id');

        $date_ar = [];
        if($date){
            $income_sql = $income_sql->where('incomes.date',date("Y-m-d",strtotime($date)));
        }

        if ($client_id) {
            $income_sql = $income_sql->where('incomes.client_id','=',$client_id);
        }

        $income = $income_sql->orderBy('incomes.date','DESC')->first();
        $total_cash = 0;
        $total_upi = 0;
        if($income){

            $income->multiple_income = Income::getMultiIncomes($income);
            $income->date = date('d-m-Y',strtotime($income->date));
            $income->total_cash = DB::table('income_entries')->where('income_id',$income->id)->sum('cash_amount');
            $total_cash  = $total_cash + $income->total_cash;
            $income->total_upi = DB::table('income_entries')->where('income_id',$income->id)->sum('upi_amount');
            $total_upi  = $total_upi + $income->total_upi;
        }

        $expenses_sql = Expense::select('expenses.*','clients.client_name')->leftJoin('clients','clients.id','=','expenses.client_id');
        
        if($date){
            $expenses_sql = $expenses_sql->where('expenses.date',date("Y-m-d",strtotime($date)));
        } 
        
        if ($client_id) {
            $expenses_sql = $expenses_sql->where('expenses.client_id','=',$client_id);
        }
        $total_expenses = $expenses_sql;
        $total_expenses = $total_expenses->sum("total_amount");
        $expenses = $expenses_sql->orderBy('expenses.date','DESC')->get();
        foreach ($expenses as $expense) {

            $expense->date = date('d-m-Y',strtotime($expense->date));
            
            $multiple_expense = DB::table('expense_entries')->where('expense_id',$expense->id)->get();

            $expense->multiple_expense = $multiple_expense;
        }


        $data['success'] = true;
        $data['clients'] = Sitting::getBranches();
        $data['income'] = $income;
        $data['expenses'] = $expenses;
        $data['total_expenses'] = $total_expenses;

        $data['total_cash'] = $total_cash;
        $data['total_upi'] = $total_upi;
        $data['date'] = date("d-m-Y", strtotime($date));
        $data['client_id'] = $client_id;

        if($request->export == 1){

            $branch = DB::table('clients')->where('id', $client_id)->first();
            $data['branch'] = $branch;

            if($branch){
                $dompdf = new Dompdf();
                $html = view('admin.incomes.summary_pdf',['data'=>$data]);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $pdfContent = $dompdf->output();

                $filename = "summary_".date("dMy").".pdf";

                $pdfPath = public_path('temp/'.$filename);
                File::ensureDirectoryExists(public_path('temp'));
                File::put($pdfPath, $pdfContent);

                $data['export_link'] = $filename;
            }else{
                $data['success'] = false;
                $data['message'] = "No data found";
            }

           
        }
        
        return Response::json($data,200,[]);
    }

}


