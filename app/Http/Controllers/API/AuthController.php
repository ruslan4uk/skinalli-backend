<?php

namespace App\Http\Controllers\API;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request) {
        
        $validatedData = $request->validate([
            'name'=>'required|max:55',
            'email'=>'email|required|unique:users',
            'password'=>'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user'=> $user, 
            'access_token'=> $accessToken
        ], 200);

    }

    public function login(Request $request) {

        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
       
        if(!auth()->attempt($loginData)) {
            return response()->json([
                'message'=>'Invalid credentials',
                'errors' => [
                    'email' => 'Логин или пароль введены неверно',
                    'password' => 'Логин или пароль введены неверно'
                ]
            ], 422);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response()->json([
            'user' => auth()->user(), 
            'access_token' => $accessToken
        ], 200);
    }

    public function user() {
        return response()->json([
            'user' => Auth::user()
        ], 200);
    }

    public function logout() {
        if(Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json([

            ], 200);
        }
    }
}
