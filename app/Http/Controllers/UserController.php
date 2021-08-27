<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\user;
use Validator;

class UserController extends Controller
{
    public function test()
    {
        return response()->json(['value'=>"c'est good"], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'FirstName' => 'required',
            'LastName' => 'required',
            'Email' => 'required|unique:user',
            'Pseudo' => 'required|unique:user',
            'Password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $user = new user();
        $user->FirstName = $request->FirstName;
        $user->LastName = $request->LastName;
        $user->Email = $request->Email;
        $user->Pseudo = $request->Pseudo;
        $user->Password = $request->Password;
        $user->Password = Hash::make($user->Password);
        $user->IsAdmin = 1;
        $user->save();

        return response()->json(['success'], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(auth()->attempt($login_credentials)){
            //generate the token for the user
            $user_login_token= auth()->user()->createToken('crytoheat')->accessToken;
            //now return this token on success login attempt
            return response()->json(['token' => $user_login_token], 200);
        }
        else{
            //wrong login credentials, return, user not authorised to our system, return error code 401
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
    }

    public function userDetail(){
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }
}
