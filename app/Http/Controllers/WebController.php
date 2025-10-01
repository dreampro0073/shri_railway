<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\WebAt;

class WebController extends Controller{
    public function home(){
        return view('front_end.home');
    }
}
