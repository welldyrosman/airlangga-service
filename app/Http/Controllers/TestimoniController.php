<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Tool;
use App\Models\testimoni;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TestimoniController extends Controller
{
    public function getall(){
        $testimoni=testimoni::all();
        return Tool::MyResponse(true,"Updated",$testimoni,200);
    }

    public function delete($id){
        $testimoni=testimoni::find($id);
        if(!$testimoni){
            throw new Exception("Testimoni tidak di temukan");
        }
        if(File::exists(public_path($testimoni->photo))){
            File::delete(public_path($testimoni->photo));
        }
        $testimoni->delete();
        return Tool::MyResponse(true,"Updated",null,200);
    }

    public function create(Request $request){
        DB::beginTransaction();
        $path="";
        try{
            $this->validate($request,[
                "nama"=>"required",
                "asal"=>"required",
                "testimoni"=>"required",
            ]);
            $data=$request->all();
            if($request->hasFile('files')){
                $imagefile=$request->file('files');
                $filename=uniqid().$imagefile->getClientOriginalExtension();
                $imagefile->file_name=$filename;
                $path = $imagefile->store('/images/testimoni/', ['disk' => 'trip_image']);
                $data["photo"]='$path';
            } else {
                $data["photo"]="";
            }

            $add=testimoni::create([
                "nama"=>$data["nama"],
                "asal"=>$data["asal"],
                "testimoni"=>$data["testimoni"],
                "photo"=>$data["photo"]
            ]);

            DB::commit();
            return Tool::MyResponse(true,"Updated",$data['photo'],200);
        }catch(Exception $e){
            DB::rollBack();
            if(File::exists(public_path($path))){
                File::delete(public_path($path));
            }
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }

    public function update(Request $request,$id){
        DB::beginTransaction();
        try{
            $path="";
            $this->validate($request,[
                "nama"=>"required",
                "asal"=>"required",
                "testimoni"=>"required",
            ]);

            $testimoni=testimoni::find($id);
            if(!$testimoni){
                throw new Exception("Testimoni tidak di temukan");
            }

            $data=$request->all();
            $input=[
                "nama"=>$data["nama"],
                "asal"=>$data["asal"],
                "testimoni"=>$data["testimoni"],
            ];
            if($request->hasFile('files')){
                if(File::exists(public_path($testimoni->photo))){
                    File::delete(public_path($testimoni->photo));
                }

                $imagefile=$request->file('files');
                $filename=uniqid().$imagefile->getClientOriginalExtension();
                $imagefile->file_name=$filename;
                $path = $imagefile->store('/images/testimoni', ['disk' => 'trip_image']);
                $input["photo"]=$path;
            }
            $testimoni->fill($input);
            $testimoni->save();
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
}
