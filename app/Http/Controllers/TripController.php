<?php

namespace App\Http\Controllers;

use App\Helpers\Tool;
use App\Models\Facilitie;
use App\Models\Trip;
use App\Models\tripdate;
use App\Models\tripimage;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use stdClass;

class TripController extends Controller
{
    public function addimages(Request $request,$id){
        DB::beginTransaction();
        try{
            $trip=Trip::find($id);
            if(!$trip){
                throw new Exception("tidak Dapat Menemukan Data");
            }
            foreach ($request->file('images') as $imagefile) {
                $image = new tripimage();
                $filename=$id."_".uniqid().$imagefile->getClientOriginalExtension();
                $imagefile->file_name=$filename;
                $path = $imagefile->store('/images/trips/'.$id, ['disk' =>   'trip_image']);
                $image->url = $path;
                $image->file_nm=$imagefile->getClientOriginalName();
                $image->trips_id=$id;
                $image->save();
            }
            DB::commit();
            return Tool::MyResponse(true,"Updated",null,200);
        }
        catch(Exception $e){
            DB::rollBack();
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }

    public function submitdates(Request $request,$id){
        DB::beginTransaction();
        try{
            $trip=Trip::find($id);
            if(!$trip){
                throw new Exception("tidak Dapat Menemukan Data");
            }
            $this->validate($request,[
                "trip_dates.*.trip_date"=>"required"
            ]);
            $tripdates=$request->input("trip_dates");
            tripdate::where('trips_id',$id)->delete();
            foreach($tripdates as $date){
                $check=tripdate::where('trip_date',$date["trip_date"])->where('trips_id',$id)->first();
                if($check){
                    throw new Exception("Tidak bisa input tanggal yang sama");
                }
                $date['trips_id']=$id;
                tripdate::create($date);
            }
            $get=tripdate::where('trips_id',$id)->get();
            DB::commit();
            return Tool::MyResponse(true,"Updated",$get,200);
        }
        catch(Exception $e){
            DB::rollBack();
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
    public function enable($id){
        return $this->toggle($id,'Y');
    }
    public function disabled($id){
        return $this->toggle($id,'N');
    }
    public function toggle($id,$status){
        DB::beginTransaction();
        try{
            $trip=Trip::find($id);
            if(!$trip){
                throw new Exception("Perjalanan tidak di temukan");
            }
            $trip->fill(["use_mk"=>$status]);
            $trip->save();
            DB::commit();
            return Tool::MyResponse(true,"Updated",$trip,200);
        }
        catch(Exception $e){
            DB::rollBack();
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }

    }

    public function getall(){
        $trips=Trip::with('facilities','images','trip_dates')->get();
        return Tool::MyResponse(true,"Query OK",$trips,200);
    }
    public function update(Request $request,$id){
        DB::beginTransaction();
        try{
            $trip=Trip::find($id);
            if(!$trip){
                throw new Exception("Perjalanan tidak di temukan");
            }
            $this->validate($request,[
                "trip_nm"=>"required",
                "city"=>"required",
                "price"=>"required",
                "isgroup"=>"required",
                'min_qty'=>"required",
                "trip_desc"=>"required",
                "facilities.*.facility"=>"required",
            ]);
            $data=$request->all();
            $trip->fill($data);
            $trip->save();
            Facilitie::where('trips_id',$id)->delete();
            $facilities=$request->input("facilities");
            foreach($facilities as $fac){
                $fac['trips_id']=$id;
                Facilitie::create($fac);
            }
            DB::commit();
            return Tool::MyResponse(true,"Query OK",$request->all(),200);
        }catch(Exception $e){
            DB::rollBack();
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
    public function deleteimg($id){
        DB::beginTransaction();
        try{
            $img=tripimage::find($id);
            if(!$img){
                throw new Exception("Gambar tidak di temukan");
            }
            if(File::exists(public_path($img->url))){
                File::delete(public_path($img->url));
            }else{
                dd('File does not exists.');
            }

            $img->delete();
            DB::commit();
            return Tool::MyResponse(true,"Query OK",null,200);
        }catch(Exception $e){
            DB::rollBack();
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
    public function delete($id){
        DB::beginTransaction();
        try{
            $trip=Trip::find($id);
            if(!$trip){
                throw new Exception("Perjalanan tidak di temukan");
            }
            $trip->delete();
            Facilitie::where('trips_id',$id)->delete();
            tripimage::where('trips_id',$id)->delete();

            DB::commit();
            return Tool::MyResponse(true,"Delete Success",null,200);
        }catch(Exception $e){
            DB::rollBack();
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
    public function create(Request $request){
        DB::beginTransaction();
        try{
            $this->validate($request,[
                "trip_nm"=>"required",
                "city"=>"required",
                "price"=>"required",
                "isgroup"=>"required",
                'min_qty'=>"required",
                "trip_desc"=>"required",
                "facilities.*.facility"=>"required",
            ]);
            $data=$request->all();
            $trip=Trip::create($data);
            $mytrip=Trip::find($trip->id);
            $facilities=$request->input("facilities");
            foreach($facilities as $fac){
                $fac['trips_id']=$mytrip->id;
                Facilitie::create($fac);
            }
            DB::commit();
            return Tool::MyResponse(true,"Query OK",$request->all(),200);
        }catch(Exception $e){
            return Tool::MyResponse(false,"ERROR",$e->getMessage(),$e->getCode()?$e->getCode():400);
        }
    }
}
