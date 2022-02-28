<?php

namespace App\Http\Controllers;

use App\Helpers\Tool;
use App\Models\about;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    //
    public function getabout(){
        $about=about::find(1);
        return Tool::MyResponse(true,"Updated",$about,200);
    }
    public function updateabout(Request $request){
        DB::beginTransaction();
            try{
            $about=about::find(1);
            $this->validate($request,["content"=>"required"]);
            $data=$request->all();
            $about->fill($data);
            $about->save();
            DB::commit();
            return Tool::MyResponse(true,"Updated",$about,200);
        }
        catch(Exception $e){
            DB::rollBack();
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
}
