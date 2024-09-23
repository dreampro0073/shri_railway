<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\Expense,App\Models\ExpenseEntry,App\Models\Sitting;
use Dompdf\Dompdf;


class ExpenseController extends Controller {

	public function index(Request $request){
		$sidebar = 'acc';
        $subsidebar = 'expenses';
        return view('admin.expenses.index',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }

    public function init(Request $request){

        $expenses = Expense::select('expenses.*','clients.client_name')->leftJoin('clients','clients.id','=','expenses.client_id');

        // if ($request->expense_account) {
        //     $expenses = $expenses->where('expenses.expense_account','=',$request->expense_account);
        // }
        
        if($request->from_date && $request->to_date){
            $expenses = $expenses->whereBetween('expenses.date',[date("Y-m-d",strtotime($request->from_date)),date("Y-m-d",strtotime($request->to_date))]);
        }
        // if ($request->expense_type) {
        //     $expenses = $expenses->where('expenses.expense_type','LIKE','%'.$request->expense_type.'%');
        // }
        if ($request->client_id) {
            $expenses = $expenses->where('expenses.client_id','=',$request->client_id);
        }
        $expenses = $expenses->orderBy('expenses.date','DESC')->get();

        $clients = DB::table('clients')->select("client_name", 'id')->where('org_id', Auth::user()->org_id)->get();

        $data['success'] = true;
        $data['expense_accounts']= Expense::expenseAccounts();
        $data['expenses'] = $expenses;
        
        $data['clients'] = $clients;
        
        return Response::json($data,200,[]);
    }

    public function editForm($expense_id = 0){
        $sidebar = 'acc';
        $subsidebar = 'expenses';
        return view('admin.expenses.add',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
            'expense_id'=>$expense_id,
        ]);
    }

    public function edit(Request $request){
        $data['expense_accounts']= Expense::expenseAccounts();

        if ($request->expense_id) {
            $expense=Expense::find($request->expense_id);
            if($expense){
                $expense->date = date('d-m-Y',strtotime($expense->date));
                    
                $multiple_expense = DB::table('expense_entries')->where('expense_id',$expense->id)->get();
                $expense->multiple_expense = $multiple_expense;
                $data['expense']=$expense;
            }
           
        }
        $clients = Sitting::getBranches();

        $data['success'] = true;
        $data['clients'] = $clients;

        $data['success'] = true;
        return Response::json($data,200,[]);
    }

    public function store(Request $request){

        $cre = [
            
        ];
        $rules=[
            
        ];
        $validator = Validator::make($cre,$rules);
        if($validator->passes()){

            $expense = Expense::find($request->id);
            $data['message']= 'Updated successfully';

            if(!$expense){
                $expense = new Expense;
                $data['message']='Added successfully';
            }   

            $expense->date = $request->has('date')?date("Y-m-d",strtotime($request->date)):date("Y-m-d");
            $expense->total_amount = $request->total_amount;
            $expense->added_by = Auth::id();
            $expense->client_id = $request->has('client_id')?$request->client_id:0;
            $expense->save();

            if ($request->multiple_expense) {
                if (sizeof($request->multiple_expense)>0) {
                    $multiple_expense = $request->multiple_expense;
                    foreach ($multiple_expense as $single_expense) {
                       
                        if (isset($single_expense['id'])) {
                            $expense_entry = ExpenseEntry::find($single_expense['id']);
                            
                        }else{
                            $expense_entry = new ExpenseEntry;
                        }

                        // dd($expense_entry);

                        $expense_entry->date = $expense->date;
                        $expense_entry->expense_id = $expense->id;
                        
                        $expense_entry->amount =(isset($single_expense['amount']))?$single_expense['amount']:null;
                        $expense_entry->gst =(isset($single_expense['gst']))?$single_expense['gst']:null;
                        
                        $expense_entry->total_amount =(isset($single_expense['total_amount']))?$single_expense['total_amount']:null;
                        $expense_entry->remarks =(isset($single_expense['remarks']))?$single_expense['remarks']:null;
                    
                        $expense_entry->expense_account =(isset($single_expense['expense_account']))?$single_expense['expense_account']:0;
                        $expense_entry->expense_type =(isset($single_expense['expense_type']))?$single_expense['expense_type']:"";
                        if (isset($single_expense['attachment'])) {
                            if ($single_expense['attachment'] !=null && $single_expense['attachment']) {
                                $expense_entry->expense_file = $single_expense['attachment'];
                            }else{
                                $expense_entry->expense_file =null;
                            }
                        }
                        $expense_entry->client_id = $expense->client_id;
                        $expense_entry->save();

                        $data['success'] =true;
                            
                       
                    }

                }else{
                    $data['success'] =false;
                    $data['message']='Please fill details';
                }
            }
        }
       
        return Response::json($data,200,array());
    }

    public function delete($expense_id){
        $expense= Expense::find($expense_id);
        if ($expense) {
            $expense->delete();
            $data['success'] = true;
            $data['message'] = 'Expense deleted successfully';
        }else{
            $data['success'] = false;
            $data['message'] = 'Expense not found';
        }
        return Response::json($data,200,array());
    }

    public function printExpense($expense_id=0){

        $dompdf = new Dompdf();

        $expense = DB::table('expenses')->select('expenses.*','clients.client_name')->leftJoin('clients','clients.id','=','expenses.client_id')->where('expenses.id',$expense_id)->first();
        if ($expense) {
            $expense->date = date('d-m-Y',strtotime($expense->date));
            
            $multiple_expense = DB::table('expense_entries')->where('expense_id',$expense->id)->get();

            $expense->multiple_expense = $multiple_expense;
        }
        // dd($expense);

        $html = view('admin.expenses.print_pdf',['expense'=>$expense]);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
    }



}


