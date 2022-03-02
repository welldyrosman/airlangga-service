<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Tool;
use App\Models\Gallery;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class GalleryController extends Controller
{
    public function getall(){
        $gallery=Gallery::all();
        return Tool::MyResponse(true,"Updated",$gallery,200);
    }
    public function coba(){
        $image = new Gallery();
        $image->title="FROM OBJ";
        $image->photo_desc="photo_desc";
        $image->url="photo_desc";
        $image->save();
        return Tool::MyResponse(true,"Updated",$image,200);
    }
    public function create(Request $request){
        DB::beginTransaction();
        $path="";
        try{
            $this->validate($request,[
                "title"=>"required",
                "photo_desc"=>"required",
            ]);
            $data=$request->all();
            $image = new Gallery();
            $image->title=$data['title'];
            $image->photo_desc=$data['photo_desc'];

            // throw new Exception("err");
            if($request->hasFile('files')){

                $imagefile=$request->file('files');
                $filename=uniqid().$imagefile->getClientOriginalExtension();
                $imagefile->file_name=$filename;
                $path = $imagefile->store('/images/gallery/', ['disk' => 'trip_image']);
                // $data["photo"]='$path';
                // $image=[
                //     "url"=>$path
                // ];
            }
            $image->url=$path;
            $image->save();
            DB::commit();
            return Tool::MyResponse(true,"Updated",$image,200);
        }catch(Exception $e){
            DB::rollBack();
            if(File::exists(public_path($path))){
                File::delete(public_path($path));
            }
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }

    public function delete($id){
        $gallery=gallery::find($id);
        if(!$gallery){
            throw new Exception("gallery tidak di temukan");
        }
        if(File::exists(public_path($gallery->photo))){
            File::delete(public_path($gallery->photo));
        }
        $gallery->delete();
        return Tool::MyResponse(true,"Updated",null,200);
    }

    public function update(Request $request,$id){
        DB::beginTransaction();
        try{
            $path="";
            $this->validate($request,[
                "title"=>"required",
                "photo_desc"=>"required",
            ]);

            $gallery=gallery::find($id);
            if(!$gallery){
                throw new Exception("gallery tidak di temukan");
            }

            $data=$request->all();
            $input=[
                "title"=>$data["title"],
                "photo_desc"=>$data["photo_desc"],
            ];
            if($request->hasFile('files')){
                if(File::exists(public_path($gallery->photo))){
                    File::delete(public_path($gallery->photo));
                }

                $imagefile=$request->file('files');
                $filename=uniqid().$imagefile->getClientOriginalExtension();
                $imagefile->file_name=$filename;
                $path = $imagefile->store('/images/gallery/', ['disk' => 'trip_image']);
                $input["url"]=$path;
            }
            $gallery->fill($input);
            $gallery->save();
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
