<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request ->jenis_kelamin,
            'alamat' => $request->alamat,
        ]);

        if($user){
            return response()->json([
                'status' => true,
                'message' => 'User Berhasil Registrasi',
                'user' => $user
            ], 201);
        }

        return response()->json([
            'status' => false
        ], 400);
    }

    public function login(Request $request){
        $validator = Validator::make($request ->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        if($validator ->all()) {
            return response()->json($validator ->errors(), 422);
        }

        $credentials = $request->only('username', 'password');

        if(!$token = auth()->guard('api') ->attempt($credentials)){
            return response()->json([
                'succes' => false,
                'message' => 'username atau password salah'
            ], 401);
        }
    
        return response()->json([
            'status' => true,
            'user' => auth() ->guard('api')->user(),
            'token' => $token
        ], 200);
    }

   
}
