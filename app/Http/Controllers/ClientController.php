<?php

namespace App\Http\Controllers;

use App\Helpers\Tool;
use App\Models\Trip;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function getall(){
        $trips=Trip::with('facilities','images','trip_dates')->get()->take(8);
        $retdata=[
            "trips"=>$trips,
            "about"=>"",
            "services"=>"",
            "teams"=>""
        ];
        return Tool::MyResponse(true,"Query OK",$retdata,200);
    }
}
