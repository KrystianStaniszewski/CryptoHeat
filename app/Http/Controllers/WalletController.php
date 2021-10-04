<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\wallet;
use Validator;

class WalletController extends Controller
{
    public function wallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Title' => 'required',
            'Type' => 'required',
            'Address'=>"required|unique:wallet",
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = auth()->user();

        if ($user) {
            $wallet = new wallet();
            $wallet->Title = $request->Title;
            $wallet->Type = $request->Type;
            $wallet->Address = $request->Address;
            $wallet->user_id = $user->id;
            $wallet->save();

            return response()->json(['success'], 201);
        } else {
            return response()->json(['Not Found'], 404);
        }
    }

    public function listWallet()
    {
        $user = auth()->user();

        if (wallet::where("User_id", $user->id)) {
            $list = wallet::where("User_id", $user->id)->get();
            return response()->json($list, 200);
        } else {
            return response()->json('Wallet Not Found', 404);
        }
    }

    public function delete($id)
    {
        $user = auth()->user();

        if (wallet::where('id', $id)) {
            $wallet = wallet::where("id", $id)->first();
            if ($wallet->User_id == $user->id) {
                $wallet->delete();
                return response()->json('Wallet has been deleted', 200);
            } else {
                return response()->json(['Unauthorized'], 401);
            }
        } else {
            return response()->json('Wallet Not Found', 401);
        }
    }
}