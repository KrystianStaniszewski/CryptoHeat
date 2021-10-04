<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\workers;
use App\Models\user;
use Validator;

class WorkersController extends Controller
{
    public function initWorkers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'connexionKey' => 'required|unique:machine',
            'name' => 'required',
            'temperature' => 0,
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $workers = new workers();
        $workers->connexionKey = $request->connexionKey;
        $workers->name = $request->name;
        $workers->user_id = $request->user_id;
        $workers->save();

        return response()->json(['success'], 201);
    }

    public function getURL(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'URL' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('ConnexionKey', $request->key)->first();

        if ($worker) {
            $worker->URL = $request->URL;
            $worker->save();
            return response()->json(['Update'], 200);
        } else {
            return response()->json(['Not Found'], 404);
        }
    }

    public function workersConnect(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('connexionKey', $request->key)->first();
        $user = auth()->user();

        if ($worker->User_id != NULL) {
            return response()->json(['Not Available'], 201); 
        }
        if ($worker && $user) {
            // $URL = $worker->URL;
            // try {
            //     $res = Http::post($URL.'check/machine', [
            //         'key' => $request->key,
            //     ]);
            //     $status = $res->getStatusCode();
            //     $body = $res->getBody();
            //     $contents = json_decode($res->getBody(), true);
            // } catch (RequestException $e) {
            //     $status = $e->getCode();
            // }

            // if ($status >= 300) {
            //     return response()->json("Erreur", $status);
            // } else {
                $worker->name = request('name');
                $worker->user_id = $user->id;
                $worker->save();
            // }
            return response()->json(['Connect'], 201);
        } else {
            return response()->json(['Not Found'], 404);
        }
    }

    public function workersDisconnect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('connexionKey', $request->key)->first();
        $user = auth()->user();

        if ($worker->User_id != $user->id) {
            return response()->json(['Unauthorized'], 401);
        }

        if ($worker && $user) {
                $worker->user_id = null;
                $worker->save();
            return response()->json(['Disconnect'], 201);
        } else {
            return response()->json(['Not Found'], 404);
        }
    }


    public function ifExist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('connexionKey', $request->key)->first();

        if ($worker) {
            return response()->json('Worker Found', 200);
        } else {
            return response()->json('Worker Not Found', 404);
        }

        return response()->json(['success'], 201);
    }

    public function listWorkers(Request $request)
    {
        $user = auth()->user();
        $id = $user->id;

        if (workers::where("User_id", $id)) {
            $list = workers::where("User_id", $id)->get();
            return response()->json($list, 200);
        } else {
            return response()->json('Worker Not Found', 404);
        }
    }

    public function updateWorkers(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'connexionKey' => 'string',
            'name' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = auth()->user();
        $worker = workers::where('id', $id)->first();

        if (!$worker || $worker->User_id != $user->id) {
            return response()->json(['Unauthorized'], 401);
        }

        if ($request->connexionKey) {
            $worker->connexionKey = $request->connexionKey;
        }
        if ($request->name) {
            $worker->name = $request->name;
        }
        if ($request->User_id) {
            $worker->User_id = $request->User_id;
        }
        if ($request->temperature) {
            $worker->temperature = $request->temperature;
        }

        $worker->save();

        return response()->json(['success'], 201);
    }

    public function deleteWorkers(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->isAdmin === 1) {
            return response()->json(['Unauthorized'], 401);
        }

        if (workers::where('id', $id)) {
            $myworker = workers::where("id", $id)->delete();
            return response()->json('Workers has been deleted', 200);
        } else {
            return response()->json('workers Not Found', 401);
        }
    }


    
}