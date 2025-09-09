<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\WebAt;

class WebcamController extends Controller
{
    public function store(Request $request)
    {
        $img = $request->image;
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);

        $data = base64_decode($img);
        $fileName = 'webcam_' . time() . '.jpg';
        Storage::disk('public')->put($fileName, $data);

        WebAt::create([
            'user_id' => 1,
            'status' => 1,
            // 'file_path' => 'storage/public/' . $fileName, // public URL path
            'file_path' => $fileName, // public URL path
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return back()->with('success', 'Image saved as '.$fileName);
    }
    public function webCamGet(){
        $photos = WebAt::all();

      
        return view('web_cam_get',compact('photos'));
    }
}
