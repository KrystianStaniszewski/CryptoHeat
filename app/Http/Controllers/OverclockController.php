<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\user;
use App\Models\gpu;
use App\Models\workers;
use App\Models\hardware;
use App\Models\Overcloking_params;
use Validator;

class OverclockController extends Controller
{
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'Key' => 'required',
            'Gpu_id' => 'required',
            'Core_clock' => 'required',
            'Memory_clock' => 'required',
            'Fan' => 'required',
            'Pw_limit' => 'required',
            'Delay' => 'required',
            'CoreVoltage' => 'required',
            'MemoryController_voltage' => 'required',
            'MemoryVoltage' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('connexionKey', $request->Key)->first();
        $user = auth()->user();

        if (!$worker) {
            return response()->json(['Not Found'], 404);
        }


        if ($worker->User_id === $user->id) {
            $gpu = gpu::where('id', $request->Gpu_id)->first();
            if (!$gpu) {
                return response()->json(['Not Found'], 404);
            } else {
                $Overcloking_params = new Overcloking_params();
                $Overcloking_params->Core_clock = $request->Core_clock;
                $Overcloking_params->Memory_clock = $request->Memory_clock;
                $Overcloking_params->Fan = $request->Fan;
                $Overcloking_params->Pw_limit = $request->Pw_limit;
                $Overcloking_params->Delay = $request->Delay;
                $Overcloking_params->CoreVoltage = $request->CoreVoltage;
                $Overcloking_params->MemoryController_voltage = $request->MemoryController_voltage;
                $Overcloking_params->MemoryVoltage = $request->MemoryVoltage;
                $Overcloking_params->Gpu_id = $request->Gpu_id;
                $Overcloking_params->save();

                return response()->json(['success'], 201);
            }
        } else {
            return response()->json(['Unauthorized'], 401); 
        }
    }

    public function get(Request $request) {
        $validator = Validator::make($request->all(), [
            'Key' => 'required',
            'Gpu_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('ConnexionKey', $request->Key)->first();

        if (!$worker) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $gpu = gpu::where('id', $request->Gpu_id)->first();
        $user = auth()->user();
        $hardware = hardware::where('Gpu_id', $request->Gpu_id)->first();

        if ($gpu) {
            if ($worker->User_id === $user->id) {
                if ($hardware->Machine_id === $worker->id) {
                    $Overcloking_params = Overcloking_params::where('Gpu_id', $gpu->id)->first();
                    if ($Overcloking_params)
                        return response()->json([$Overcloking_params], 201);
                    else
                        return response()->json(['No Overclocking Params'], 200); 
                } else {
                    return response()->json(['Unauthorized'], 401); 
                }
            } else {
                return response()->json(['Unauthorized'], 401); 
            }
        } else {
            return response()->json(['Not Found'], 404);
        }
    }

    public function getWorkerGpuOverclock(Request $request) {
        $validator = Validator::make($request->all(), [
            'Key' => 'required',
            'Gpu_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('ConnexionKey', $request->Key)->first();

        if (!$worker) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $gpu = gpu::where('id', $request->Gpu_id)->first();
        $hardware = hardware::where('Gpu_id', $request->Gpu_id)->first();

        if ($gpu) {
            if ($hardware->Machine_id === $worker->id) {
                $Overcloking_params = Overcloking_params::where('Gpu_id', $gpu->id)->first();
                return response()->json([$Overcloking_params], 201);
            } else {
                return response()->json(['Unauthorized'], 401); 
            }
        } else {
            return response()->json(['Not Found'], 404);
        }
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'Key' => 'required',
            'Gpu_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('ConnexionKey', $request->Key)->first();

        if (!$worker) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $gpu = gpu::where('id', $request->Gpu_id)->first();
        $user = auth()->user();
        $hardware = hardware::where('Gpu_id', $request->Gpu_id)->first();

        if ($gpu) {
            if ($worker->User_id === $user->id) {
                if ($hardware->Machine_id === $worker->id) {
                    $Overcloking_params = Overcloking_params::where('Gpu_id', $gpu->id)->first();
                    if ($request->input('Core_clock')){
                        $Overcloking_params->Core_clock = $request->input('Core_clock');
                    }
                    if ($request->input('Memory_clock')){
                        $Overcloking_params->Memory_clock = $request->input('Memory_clock');
                    }
                    if ($request->input('Fan')){
                        $Overcloking_params->Fan = $request->input('Fan');
                    }
                    if ($request->input('Pw_limit')){
                        $Overcloking_params->Pw_limit = $request->input('Pw_limit');
                    }
                    if ($request->input('Delay')){
                        $Overcloking_params->Delay = $request->input('Delay');
                    }
                    if ($request->input('CoreVoltage')){
                        $Overcloking_params->CoreVoltage = $request->input('CoreVoltage');
                    }
                    if ($request->input('MemoryController_voltage')){
                        $Overcloking_params->MemoryController_voltage = $request->input('MemoryController_voltage');
                    }
                    if ($request->input('MemoryVoltage')){
                        $Overcloking_params->MemoryVoltage = $request->input('MemoryVoltage');
                    }
        
                    $Overcloking_params->save();

                    return response()->json([$Overcloking_params], 201);
                } else {
                    return response()->json(['Unauthorized'], 401); 
                }
            } else {
                return response()->json(['Unauthorized'], 401); 
            }
        } else {
            return response()->json(['Not Found'], 404);
        }
    }
}
