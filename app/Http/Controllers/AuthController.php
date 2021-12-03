<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validate = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'address' => 'required|string|max:255',
            'handphone' => 'required|string',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'name' => $validate['name'],
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
            'token' => $token
        ];

        return response($response, 201);
    }
}
