<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IncomeEntry extends Model {

    protected $table = 'income_entries';
        
}


