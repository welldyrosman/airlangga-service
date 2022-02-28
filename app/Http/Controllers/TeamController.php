<?php

namespace App\Http\Controllers;

use App\Helpers\Tool;
use App\Models\team;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function getall(){
        $teams=team::all();
        return Tool::MyResponse(true,"Updated",$teams,200);
    }
    public function delete($id){
        $teams=team::find($id);
        if(!$teams){
            throw new Exception("Team tidak di temukan");
        }
        if(File::exists(public_path($teams->photos))){
            File::delete(public_path($teams->photos));
        }
        $teams->delete();
        return Tool::MyResponse(true,"Updated",null,200);
    }
    public function update(Request $request,$id){
        DB::beginTransaction();
        try{
            $path="";
            // /throw new Exception("Team tidak di temukan");
            $this->validate($request,[
                "nama"=>"required",
                "jabatan"=>"required",
                "wa"=>"required",
                "fb"=>"required",
                "ig"=>"required",
            ]);

            $teams=team::find($id);
            if(!$teams){
                throw new Exception("Team tidak di temukan");
            }

            $data=$request->all();
            $input=[
                "nama"=>$data["nama"],
                "jabatan"=>$data["jabatan"],
                "wa"=>$data["wa"],
                "fb"=>$data["fb"],
                "ig"=>$data["ig"],
            ];
            if($request->hasFile('files')){
                if(File::exists(public_path($teams->photos))){
                    File::delete(public_path($teams->photos));
                }

                $imagefile=$request->file('files');
                $filename=uniqid().$imagefile->getClientOriginalExtension();
                $imagefile->file_name=$filename;
                $path = $imagefile->store('/images/teams', ['disk' => 'trip_image']);
                $input["photo"]=$path;
            }
            $teams->fill($input);
            $teams->save();
            DB::commit();
            return Tool::MyResponse(true,"Updated", $request->all(),200);
        }catch(Exception $e){
            DB::rollBack();
            // if(File::exists(public_path($path))){
            //     File::delete(public_path($path));
            // }
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
    public function create(Request $request){
        DB::beginTransaction();
        $path="";
        try{
            $this->validate($request,[
                "nama"=>"required",
                "jabatan"=>"required",
                "wa"=>"required",
                "fb"=>"required",
                "ig"=>"required"
            ]);
            $data=$request->all();
            if($request->hasFile('files')){
                $imagefile=$request->file('files');
                $filename=uniqid().$imagefile->getClientOriginalExtension();
                $imagefile->file_name=$filename;
                $path = $imagefile->store('/images/teams/', ['disk' => 'trip_image']);
                $data["photo"]=$path;
            }

            team::create([
                "nama"=>$data["nama"],
                "jabatan"=>$data["jabatan"],
                "wa"=>$data["wa"],
                "fb"=>$data["fb"],
                "ig"=>$data["ig"],
                "photo"=>$data["photo"]
            ]);
            DB::commit();
            return Tool::MyResponse(true,"Updated",null,200);
        }catch(Exception $e){
            DB::rollBack();
            if(File::exists(public_path($path))){
                File::delete(public_path($path));
            }
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
}
