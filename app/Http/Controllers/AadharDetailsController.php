<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User;


class AadharDetailsController extends Controller {	

	public function fetchData(Request $request){
		$details = DB::table("aadhar_details")->where("aadhar_no", $request->aadhar_no)->first();
		$data['newAadharFlag'] = false;
		if(!$details){
			$get_id = DB::table("aadhar_details")->insertGetId([
				"aadhar_no"=>$request->aadhar_no,
				"created_at"=>date("Y-m-d H:i:s"), 
                "upload_status"=>0,
			]);
			$data['newAadharFlag'] = true;
			$details = DB::table("aadhar_details")->where("id", $get_id)->first();
		}

		$data["success"] = true;
		$data['details'] = $details;

		return Response::json($data, 200, []);
	}



	public static function getFileName($name, $extension){
        $name = str_replace(' ', '_', $name);
        $name = preg_replace('/[^A-Za-z0-9_\.\-]/', '', $name);
        $name_ex = explode('.', $name);
        $filename = "";
        foreach($name_ex as $index => $n){
            if($index <= sizeof($name_ex) - 2){
                $filename .= $n;
            }
        }

        $filename = $filename."_".strtotime("now").".".$extension;

        return $filename;
    }

    public function uploadByMobileFile($id){
        $aadhar = DB::table("aadhar_details")->where("upload_status", "<", 2)->where("id", $id)->first();
        if($aadhar){
            return view('aadhar_uploads', ['aadhar'=>$aadhar]);
        } else {
            return view('error');
        }
    }

    public function postUploadByMobileFile(Request $request, $id){
        $aadhar = DB::table("aadhar_details")->where("upload_status", "<", 2)->where("id", $id)->first();
        if($aadhar){
            $count = $aadhar ? $aadhar->upload_status : 0;
            $front = $aadhar ? $aadhar->front : "";
            $back = $aadhar ? $aadhar->back : "";

            if($request->aadhar_front){
                $destinationPath = "aadhar_uploads/";
                $extension = $request->file('aadhar_front')->getClientOriginalExtension();
                if(!in_array($extension, User::onlyImages())){
                    return Redirect::back()->withInput()->with('failure','Please upload valid file , valid extensions are jpg,jpeg,png');

                }
                $file = str_replace(' ','-','front_'.strtotime("now").'.'.$extension);
                if($request->aadhar_front->move($destinationPath,$file)){  
                    $front = $destinationPath.$file;
                    $count++;
                }
            }

            if($request->aadhar_back){
                $destinationPath = "aadhar_uploads/";
                $extension = $request->file('aadhar_back')->getClientOriginalExtension();
                if(!in_array($extension, User::onlyImages())){
                    return Redirect::back()->withInput()->with('failure','Please upload valid file , valid extensions are jpg,jpeg,png');

                }
                $file = str_replace(' ','-','back_'.strtotime("now").'.'.$extension);
                if($request->aadhar_back->move($destinationPath,$file)){  
                    $back = $destinationPath.$file;
                    $count++;
                }
            }

        
            DB::table("aadhar_details")->where("id", $id)->update([
                "upload_status"=> $count,
                "front"=>$front,
                "back"=>$back,
            ]);
            return view('thanku');
        } else {
            return view('error');
        }
    }

    public function uploadFile(Request $request){

        $destination = 'aadhar_uploads/';
        $maxSize = 512000;
        if($request->media){
            $file = $request->media;
            $name = $request->media->getClientOriginalName();
            $extension = $request->media->getClientOriginalExtension();
            $size = $request->media->getSize(); 

            $name = str_replace(".".$extension, "", $name);
            
            if($size > $maxSize){
                $data['success'] = false;
                $data['message'] = 'File size exceeds the maximum limit of 500KB';
            } elseif(in_array($extension, User::onlyImages())) {

                $name = $request->name."_".strtotime("now").".".$extension;

                Image::make($file)->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->fit(600,800)->save($destination.$name);

                $data["path"] = $destination.$name;
                $data["url"] = url($destination.$name);
                $data["success"] = true;
                
            } else {
                $data['success'] = false;
                $data['message'] = 'Invalid file format';
            }
        } else {
            $data['success'] = false;
            $data['message'] = 'File not found';
        }

        return Response::json($data, 200, array());
    }

    public function uploadFileXX(Request $request){
        $destination = 'aadhar_uploads/';
        if($request->file('media')){
            $file = $request->file('media');

            $extension = $request->file('media')->getClientOriginalExtension();

            if(!in_array($extension, User::onlyImages())){
                $data['success'] = false;
                $data['message'] ='Please upload the valid file(jpg/png/JPEG)';

                return Response::json($data, 200, array());

            }
            
            $name = $file->getClientOriginalName();
            $name = $request->name."_".strtotime("now").".".$extension;

            $file = $file->move($destination, $name);
            $data["success"] = true;
            $data["path"] = $destination.$name;
            $data["url"] = url($destination.$name);

        }else{
            $data['success'] = false;
            $data['message'] ='file not found';
        }

        return Response::json($data, 200, array());

    } 

	
}
