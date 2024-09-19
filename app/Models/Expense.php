<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Expense extends Model {


    protected $table = 'expenses';

    public static function expenseAccounts(){
        $expense_accounts = ['1'=>'Cash','2'=>'UPI'];
        return $expense_accounts;
    }

    public static function incomeTypes(){
        $expense_accounts = [1=>'Sitting Cash',2=>'Sitting UPI',3=>'Massage Cash',4=>'Massage UPI',5=>'Locker Cash',6=>'Locker UPI',7=>'Canteen Cash',8=>'Canteen UPI',];
        return $expense_accounts;
    }
        
}


