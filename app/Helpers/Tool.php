<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Auth;

class Tool{
    public static function MyResponse($issuccess,$err,$data,$responsecode){
        $msg="";
        $errcode=$responsecode;
        return response()->json([
            "success"=>$issuccess,
            "messages"=>$err,
            "data"=>$data
        ],$errcode);
    }
}
?>
