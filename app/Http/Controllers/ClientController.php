<?php

namespace App\Http\Controllers;

use App\Helpers\Tool;
use App\Models\about;
use App\Models\ourservice;
use App\Models\team;
use App\Models\Trip;
use Exception;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function gettripbyid($id){
        try{
            $trips=Trip::with('facilities','images','trip_dates')->find($id);
            if(!$trips){
                throw new Exception("Cannot Fount Trip");
            }
            return Tool::MyResponse(true,"Query OK",$trips,200);
        }
        catch(Exception $e){
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
    public function getall(){
        $trips=Trip::with('facilities','images','trip_dates')->get()->take(8);
        $about=about::first();
        $service=ourservice::all();
        $teams=team::all();
        $retdata=[
            "trips"=>$trips,
            "about"=>$about,
            "services"=>$service,
            "teams"=>$teams
        ];

        return Tool::MyResponse(true,"Query OK",$retdata,200);
    }
}
