<?php

namespace App\Http\Controllers;

use App\Helpers\Tool;
use App\Models\ourservice;
use Exception;
use Illuminate\Http\Request;

class OurServController extends Controller
{
    public function create(Request $request){
        try{
            $this->validate($request,[
                "serv_nm"=>"required",
                "serv_desc"=>"required",
            ],
            [
                'serv_nm.required' => 'Nama Layanan Tidak Boleh Kosong',
                'serv_desc.required' => 'Deskripsi Layanan Tidak Boleh Kosong'
            ]);
            $data=$request->all();
            $data['use_mk']='Y';
            $max=ourservice::max('seq');
            $data['seq']= $max+1;
            $services=ourservice::create($data);
            return Tool::MyResponse(true,"Query OK",$services,200);
        }catch(Exception $e){
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),400);
        }
    }
    public function update(Request $request,$id){
        try{
            $this->validate($request,[
                "serv_nm"=>"required",
                "serv_desc"=>"required",
                "seq"=>"required",
                "use_mk"=>"required",
            ]);
            $data=$request->all();
            $services=ourservice::find($id);
            if(!$services){
                throw new Exception("Cannot Found Services");
            }
            $services->fill($data);
            $services->save();
            return Tool::MyResponse(true,"Query OK",$services,200);
        }catch(Exception $e){
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),400);
        }
    }
    public function getall(){
        $services=ourservice::orderBy('seq','asc')->get();
        return Tool::MyResponse(true,"Query OK",$services,200);
    }
    public function delete($id){
        $services=ourservice::find($id);
        if(!$services){
            throw new Exception("Cannot Found Services");
        }
        $services->delete();
        return Tool::MyResponse(true,"Query OK",null,200);
    }
}
