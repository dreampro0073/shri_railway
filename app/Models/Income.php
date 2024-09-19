<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Income extends Model {


    protected $table = 'incomes';
        
}


