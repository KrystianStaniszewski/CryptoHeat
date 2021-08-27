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
            'connexionKey' => 'required',
            'name' => 'required',
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
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $worker = workers::where('connexionKey', $request->key)->first();
        $user = auth()->user();

        if ($worker && $user) {
            $URL = $worker->URL;
            try {
                $res = Http::post($URL.'check/machine', [
                    'key' => $request->key,
                ]);
                $status = $res->getStatusCode();
                $body = $res->getBody();
                $contents = json_decode($res->getBody(), true);
            } catch (RequestException $e) {
                $status = $e->getCode();
            }

            if ($status >= 300) {
                return response()->json("Erreur", $status);
            } else {
                $worker->name = request('name');
                $worker->user_id = $user->id;
                $worker->save();
            }
            return response()->json(['Connect'], 201);
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

    public function listWorkers(Request $request, $id)
    {
        if (workers::where("User_id", $id)) {
            $list = workers::where("User_id", $id)->get();
            return response()->json($list, 200);
        } else {
            return response()->json('Worker Not Found', 404);
        }
    }
}