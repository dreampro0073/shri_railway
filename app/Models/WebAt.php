<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use DB;

class WebAt extends Model
{

    protected $table = 'web_at';
    protected $fillable = ['file_path','user_id','status','created_at'];

}