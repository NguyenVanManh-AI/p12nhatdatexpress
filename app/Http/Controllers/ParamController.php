<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ParamController extends Controller
{
    private GroupService $groupService;

    public function __construct()
    {
        $this->groupService = new GroupService;
    }

    public function get_district(Request $request)
    {
        $districts = DB::table('district')->select('id', 'district_name')->where('province_id', $request->province_id)->get();
        return response()->json(['districts' => $districts, 'status' => 'success'], 200);
    }
    public function get_ward(Request $request)
    {
        $wards = DB::table('ward')->select('id', 'ward_name')->where('district_id', $request->district_id)->get();
        return response()->json(['wards' => $wards, 'status' => 'success'], 200);
    }
    public function get_progress(Request $request)
    {
        $progress = DB::table('progress')->select('id', 'progress_name')->where('group_id', $request->group_id)->get();
        return response()->json(['progress' => $progress, 'status' => 'success'], 200);
    }
    public function get_furniture(Request $request)
    {
        $furniture = DB::table('furniture')->select('id', 'furniture_name')->where('group_id', $request->group_id)->get();
        return response()->json(['furniture' => $furniture, 'status' => 'success'], 200);
    }
    public function get_group_child(Request $request){

        $data =$this->groupService->getChildren($request->group_id);
        $group_child = $this->groupService->prepareData($data);

        $result ="";
        $result = $result."<option selected disabled>Mô hình</option>";
        foreach ($group_child as $item){
            if(!isset($item->child)){
                $result = $result."<option value='$item->id'>".$item->group_name."</option>";
            }
            else {
                $result = $result."<option value='$item->id'>---- ".$item->group_name."</option>";
            }
        }
//        dd($result);
        return response()->json(['group_child'=>$result, 'status' => 'success'], 200);
    }
}
