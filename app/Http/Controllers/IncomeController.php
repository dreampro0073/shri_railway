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
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;

class IncomeController extends Controller {

	public function index(Request $request){
		$sidebar = 'income';
        $subsidebar = 'income';
        return view('admin.incomes.index',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function init(Request $request){

        $incomes = DB::table('incomes')->select('incomes.*','clients.client_name')->leftJoin('clients','clients.id','=','incomes.client_id');
        if ($request->from) {
            $incomes = $incomes->where('incomes.from','LIKE','%'.$request->from.'%');
        }
        $date_ar = [];
        if($request->from_date && $request->to_date){
            $incomes = $incomes->whereBetween('incomes.date',[date("Y-m-d",strtotime($request->from_date)),date("Y-m-d",strtotime($request->to_date))]);

        }

        if ($request->client_id) {
            $incomes = $incomes->where('incomes.client_id','=',$request->client_id);
        }

        if ($request->income_type) {
            $incomes = $incomes->where('incomes.income_type','=',$request->income_type);
        }

        
        $incomes = $incomes->orderBy('incomes.date','DESC')->get();

        $income_types = Expense::incomeTypes();

        $total_income = 0;

        foreach ($incomes as $key => $income) {
            $income->show_income_type = (isset($income->income_type))?$income_types[$income->income_type]:'NA';
        }

        $clients = DB::table('clients')->select("client_name", 'id')->where('org_id', Auth::user()->org_id)->get();

        $data['success'] = true;
        $data['income_types']= Expense::incomeTypes();
        $data['clients'] = $clients;
        $data['incomes'] = $incomes;
        $data['total_income'] = $total_income;
        
        return Response::json($data,200,[]);
    }

    public function editForm($income_id = 0){
        $sidebar = 'income';
        $subsidebar = 'income';
        return view('admin.incomes.add',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
            'income_id'=>$income_id,
        ]);
    }

    public function edit(Request $request){

        $data['income_types']= Expense::incomeTypes();
        $date = $request->date?$request->date : date("Y-m-d");
        $client_id = $request->client_id ? $request->client_id : Auth::user()->client_id;
        $income = DB::table('incomes')->where("date", date('Y-m-d',strtotime($date)))->where("client_id", $client_id)->first();
        if ($income) {
            $income->date = date('d-m-Y',strtotime($income->date));
            $multiple_income = DB::table('income_entries')->where('income_id',$income->id)->get();
            $income->multiple_income = $multiple_income;
        }
        $data['income'] = $income;
        $data['client_id'] = $client_id;
        $data['date'] = date('d-m-Y',strtotime($date));
        $clients = DB::table('clients')->select("client_name", 'id')->where('org_id', Auth::user()->org_id)->get();

        $data['success'] = true;
        $data['clients'] = $clients;

        return Response::json($data,200,[]);
    }    

    public function editX(Request $request){

        $data['income_types']= Expense::incomeTypes();

        if ($request->income_id || $request->date) {
            if($request->income_id){
                $income = DB::table('incomes')->find($request->income_id);
            }else{
                $income = DB::table('incomes')->where("date", date('Y-m-d',strtotime($request->date)))->first();
            }
            if ($income) {
                $income->date = date('d-m-Y',strtotime($income->date));
                $multiple_income = DB::table('income_entries')->where('income_id',$income->id)->get();
                $income->multiple_income = $multiple_income;
            }
            $data['income'] = $income;
        }
        $clients = DB::table('clients')->select("client_name", 'id')->where('org_id', Auth::user()->org_id)->get();

        $data['success'] = true;
        $data['clients'] = $clients;

        return Response::json($data,200,[]);
    }

    public function store(Request $request){

        $cre = [
            
        ];
        $rules=[
            
        ];
        $validator = Validator::make($cre,$rules);
        if ($validator->passes()) {
            $income = Income::find($request->id);
            $data['message']= 'Updated successfully';

            if(!$income){
                $income = new Income;
                $data['message']='Added successfully';
            }   

            $income->date = $request->has('date')?date("Y-m-d",strtotime($request->date)):date("Y-m-d");
            $income->total_amount = $request->total_amount;
            $income->back_balance = $request->back_balance;
            $income->all_total = $request->all_total;
            $income->added_by = Auth::id();
            $income->client_id = $request->client_id;
            $income->save();

            if ($request->multiple_income) {
                if (sizeof($request->multiple_income)>0) {
                    $multiple_income = $request->multiple_income;
                    foreach ($multiple_income as $single_income) {
                        if (isset($single_income['id'])) {
                            $income_entry = IncomeEntry::find($single_income['id']);       
                        }else{
                            $income_entry = new IncomeEntry;
                            
                        }
                        $income_entry->from =(isset($single_income['from']))?$single_income['from']:0;
                        $income_entry->date =$income->date;
                        
                        

                        if (isset($single_income['attachment'])) {
                            if ($single_income['attachment'] !=null && $single_income['attachment']) {
                                $income_entry->income_file = $single_income['attachment'];
                            }else{
                                $income_entry->income_file =null;
                            }
                        }

                        $income_entry->amount =(isset($single_income['amount']))?$single_income['amount']:null;
                        
                        $income_entry->income_type =(isset($single_income['income_type']))?$single_income['income_type']:0;
                        $income_entry->remarks =(isset($single_income['remarks']))?$single_income['remarks']:null;
                        $income_entry->income_id = $income->id;
                        $income_entry->client_id = $income->client_id;
                        $income_entry->save();

                        $data['success'] =true;
                    }

                }else{
                    $data['success']=false;
                    $data['message'] =$validator->errors()->first();
                }
            }
        }else{
            $data['success'] =false;
            $data['message']='Please fill details';
        }

        
        return Response::json($data,200,array());
    }

    public function delete($income_id){
        $income= Income::find($income_id);
        if ($income) {
            $income->delete();
            $data['success'] = true;
            $data['message'] = 'Income deleted successfully';
        }else{
            $data['success'] = false;
            $data['message'] = 'Income not found';
        }
        return Response::json($data,200,array());
    }

    public function printIncome($income_id=0){

        $dompdf = new Dompdf();

        $income = DB::table('incomes')->select('incomes.*','clients.client_name')->leftJoin('clients','clients.id','=','incomes.client_id')->where('incomes.id',$income_id)->first();
        if ($income) {
            $income->date = date('d-m-Y',strtotime($income->date));
            
            $multiple_income = DB::table('income_entries')->where('income_id',$income->id)->get();

            $income_types = Expense::incomeTypes();

           
            foreach ($multiple_income as $key => $item) {
                $item->show_income_type = (isset($item->income_type))?$income_types[$item->income_type]:'NA';
            }

            $income->multiple_income = $multiple_income;
        }
        // dd($income);

        $html = view('admin.incomes.print_pdf',['income'=>$income]);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
    }

    public function summary(Request $request){
        $sidebar = 'summary';
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

        $total_income = 0;

        foreach ($incomes as $key => $income) {
            $income->show_income_type = (isset($income->income_type))?$income_types[$income->income_type]:'NA';
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

        $clients = DB::table('clients')->select("client_name", 'id')->where('org_id', Auth::user()->org_id)->get();

        $data['success'] = true;
        $data['clients'] = $clients;
        $data['income_types'] = $income_types;
        $data['incomes'] = $incomes;
        $data['expenses'] = $expenses;
        $data['total_expenses'] = $total_expenses;
        $data['total_incomes'] = $total_incomes;


        

        if($request->export == 1){
            $data["to_date"] = $to_date;
            $data["from_date"] = $from_date;
            $data["branch"] = DB::table('clients')->where('id', $client_id)->first();
            $dompdf = new Dompdf();
            $html = view('admin.incomes.summary_pdf',['data'=>$data]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            // $dompdf->stream();
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


