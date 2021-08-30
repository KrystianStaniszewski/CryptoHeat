<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\user;
use Validator;

class UserController extends Controller
{
    public function register(Request $request) {
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
        $user->isAdmin = 0;
        $user->save();

        return response()->json(['success'], 201);
    }

    public function login(Request $request) {
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

    public function userDetail() {
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }

    public function userUpdate(Request $request) {
        $user = auth()->user();

        if ($user) {

            $validator = Validator::make($request->all(), [
                'Pseudo' => 'regex:/^[a-zA-Z0-9_-]/|unique:user|string',
                'Email' => 'email:rfc,dns|unique:user|string',
                'Password' => 'string',
                'FirstName' => 'string',
                'LastName' => 'string',
                'IsAdmin' => 'integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['message'=>"Bad Request", "code"=>10001, "data"=>$validator->errors()], 400);
            }

            if ($request->input('FirstName')){
                $user->FirstName = $request->input('FirstName');
            }
            if ($request->input('LastName')){
                $user->LastName = $request->input('LastName');
            }
            if ($request->input('IsAdmin')){
                $user->isAdmin = $request->input('IsAdmin');
            }
            if ($request->input('Pseudo')){
                $user->Pseudo = $request->input('Pseudo');
            }
            if ($request->input('Email')){
                $user->email = $request->input('Email');
            }
            if ($request->input('Password')){
                $user->Password = $request->input('Password');
                $user['Password'] = bcrypt($user['Password']);
            }

            $user->save();

            return response()->json(['success'], 201);
        } else {
            return response()->json(['message'=>'Unauthorized'], 401);
        }
    }
    
}
