<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\user;
use App\Models\gpu;
use App\Models\workers;
use App\Models\hardware;
use Validator;

class GpuController extends Controller
{
    public function initGpu(Request $request) {
        $validator = Validator::make($request->all(), [
            'Key' => 'required',
            'Name' => 'required|unique:gpu',
            'Brand' => 'required',
            'Ram' => 'required|integer',
            'Ram_Brand' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('ConnexionKey', $request->Key)->first();

        if (!$worker) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        // if ($worker->User_id === NULL || $worker->User_id === $user->id) {
            $gpu = new gpu();
            $gpu->Name = $request->Name;
            $gpu->Brand = $request->Brand;
            $gpu->Ram = $request->Ram;
            $gpu->Ram_Brand = $request->Ram_Brand;
            $gpu->save();

            $hardware = new hardware();
            $hardware->Gpu_id = $gpu->id;
            $hardware->Machine_id = $worker->id;
            $hardware->save();
        // } else {
        //     return response()->json(['Unauthorized'], 401);
        // }

        return response()->json([$gpu], 201);
    }

    public function getGpu($id) {
        $gpu = gpu::where('id', $id)->first();

        if ($gpu) {
            return response()->json([$gpu], 201);
        } else {
            return response()->json(['Not Found'], 404);
        }
    }

    public function updateGpu(Request $request, $id) {
        $gpu = gpu::where('id', $id)->first();
        $user = auth()->user();
        $hardware = hardware::where('Gpu_id', $id)->first();
        $worker = workers::where('id', $hardware->Machine_id)->first();

        if ($gpu) {
            if ($worker->User_id === $user->id) {

                if ($request->input('Name')){
                    $gpu->Name = $request->input('Name');
                }
                if ($request->input('Brand')){
                    $gpu->Brand = $request->input('Brand');
                }
                if ($request->input('Ram')){
                    $gpu->Ram = $request->input('Ram');
                }
                if ($request->input('Ram_Brand')){
                    $gpu->Ram_Brand = $request->input('Ram_Brand');
                }
    
                $gpu->save();

                return response()->json([$gpu], 201);
            } else {
                return response()->json(['Unauthorized'], 401); 
            }
        } else {
            return response()->json(['Not Found'], 404);
        }
    }


    public function getList(Request $request) {
        $validator = Validator::make($request->all(), [
            'Key' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = auth()->user();
        $worker = workers::where('connexionKey', $request->Key)->first();

        if (!$worker) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if ($worker->User_id === $user->id) {
            $hardware = hardware::where('Machine_id', $worker->id)->get();
            $gpuslist = [];

            foreach ($hardware as &$item) {
                $gpu = gpu::where('id', $item->id)->first();
                array_push($gpuslist, $gpu);
            }
            return response()->json([$gpuslist], 201);
        } else {
            return response()->json(['Unauthorized'], 401); 
        }
    }

    public function getHardwareList(Request $request) {
        $validator = Validator::make($request->all(), [
            'Key' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = auth()->user();
        $worker = workers::where('connexionKey', $request->Key)->first();

        if (!$worker) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if ($worker->User_id === $user->id) {
            $hardware = hardware::where('Machine_id', $worker->id)->get();
            $gpuslist = [];

            return response()->json($hardware, 201);
        } else {
            return response()->json(['Unauthorized'], 401); 
        }
    }
}
