<?php

namespace App\Http\Controllers;

use App\Helpers\Tool;
use App\Models\tran;
use App\Models\Trip;
use App\Models\tripdate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class transController extends Controller
{
    public function submit(Request $request){
        DB::beginTransaction();
        try{
            $this->validate($request,[
                "trip_id"=>"required",
                "tripdate_id"=>"required",
                "qty"=>"required",
            ]);

            $arg=$request->all();
            $trip=Trip::find($arg["trip_id"]);
            if(!$trip){
                throw new Exception("Cannot FOund Trip");
            }
            $date=tripdate::where("trips_id",$arg["trip_id"])->where("id",$arg["tripdate_id"])->first();
            if(!$date){
                throw new Exception("Cannot Found Schedule");
            }
            $total=$arg["qty"]*$trip->price;
            $user = Auth::guard('userclients')->user();
            $trans=tran::create([
                "member_id"=>$user->id,
                "trip_id"=>$trip->id,
                "tripdate_id"=>$date->id,
                "qty"=>$arg["qty"],
                "total"=>$total,
                "status"=>"1",
            ]);
            DB::commit();
            return Tool::MyResponse(true,"Query OK",$trans,200);
        }catch(Exception $e){
            DB::rollBack();
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
}
