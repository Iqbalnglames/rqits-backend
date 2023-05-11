<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'photo_source' => 'required',
            'username' => 'required|unique:users',
            'serial_number' => 'required',
            'password' => 'required|min:8|confirmed',

        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $photo_source = $request->file('user_photo');
        $photo_source -> storeAs('public/user_photo', $photo_source->hashName());

        $user = User::create([
            'photo_source' => $photo_source->hashName(),
            'nama' => $request-> nama,
            'username' => $request -> username,
            'serialNumber' => $request -> serialNumber,
            'password' => Hash::make($request -> passowrd),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'register berhasil',
            'data' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request -> all(),[
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator-> fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('username', $request->username)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'success' => false,
                'message' => 'password atau username salah',                
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => $user,
            'token' => $user->createToken('authToken')->accessToken,
        ]);

    }
    public function logout(Request $request)
    {
        $removeToken = $request->user()->tokens()->delete();

        if($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ]);
        }
    }
}
