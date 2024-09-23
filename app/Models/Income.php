<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Income extends Model {


    protected $table = 'incomes';

    public static function getIncomes($request){
        
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

        foreach ($incomes as $key => $income) {
            $income->show_income_type = (isset($income->income_type))?$income_types[$income->income_type]:'NA';
        }

        return $incomes;
    }

    public static function getMultiIncomes($income){
        $multiple_income = DB::table('income_entries')->where('income_id',$income->id)->get();
        $income_types = Expense::incomeTypes();
        foreach ($multiple_income as $key => $item) {
            $item->show_income_type = (isset($item->income_type))?$income_types[$item->income_type]:'NA';
        }

        return $multiple_income;
    }
        
}


