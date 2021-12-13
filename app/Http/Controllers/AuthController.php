<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validate = $request->validate([
            'fullname' => 'required|string|max:50',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'address' => 'required|string|max:255',
            'handphone' => 'required|string',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'fullname' => $validate['fullname'],
            'email' => $validate['email'],
            'password' => bcrypt($validate['password']),
            'address' => $validate['address'],
            'handphone' => $validate['handphone'],
            'role' => $validate['role']
        ]);

        $token = $user->createToken('usertoken')->plainTextToken;

        $response = [
            'message' => 'Register Successfull',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $validate = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email',$validate['email'])->first();

        if (!$user || !Hash::check($validate['password'], $user->password)) {
            return response([
                'message' => 'Login Failed'
            ], 401);
        }

        $token = $user->createToken('usertoken')->plainTextToken;

        $response = [
            'message' => 'Login Successful',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        return response($response, 200);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logout Successful'
        ];
    }

    public function listUser(){
        $listUser = User::all();

        return response([
            'message' => 'list success',
            'data' => $listUser
        ],200);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response([
            'message' => 'Delete User success'
        ], 202);
    }
}
