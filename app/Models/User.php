<?php

namespace App\Models;

use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\MailQueue;

class User extends Authenticatable {

    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    //protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function fileExtensions(){
        return array (
            "pdf" , "jpg" , "jpeg", "xls","xlsx" ,"png" , "JPG" ,"JPEG" , "PDF" ,"PNG","XLSX","XLS","csv","CSV","docx","DOCX","pdf","PDF","svg","SVG"
        );
    }

    public static function onlyImages(){
        return array("jpg","JPG","JPEG","jpeg","png","PNG");
    }

    public static function checkHrType(){
        $check = DB::table("clients")->where("id", Auth::user()->client_id)->first();
        if($check->rate_type == 2){
            return true;
        } else {
            return false;
        }
    }

    public static function getServiceIds(){
        $service_ids = DB::table('client_services')->where("client_id", Auth::user()->client_id)->where('status',1)->pluck('services_id')->toArray();

        return $service_ids;
    }
    

        
}
