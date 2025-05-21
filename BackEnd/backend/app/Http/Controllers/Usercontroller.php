<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class Usercontroller extends Controller
{
    //for register
 public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|max:12',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json([
        'message' => 'User Registered',
        'user' => $user,
        'token' => $token,
    ], 201);
}

//for login
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8|max:12',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user) {   
        return response()->json(['error' => 'Invalid Email'], 401);
    }elseif(!Hash::check($request->password,$user->password)){
        return response()->json(['error' => 'Incorrect Password'], 401);
    }

    
    $token = JWTAuth::fromUser($user);

    return response()->json([
        'message' => 'Login Successfully',
        'user' => $user->makeHidden(['password']),
        'token' => $token,
    ], 201);
}
//show dashboard

public function dashboard(Request $request)
{
    
    try{
       $user = JWTAuth::parseToken()->authenticate(); 
    }catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
          return response()->json(['error' => 'Token Invalid'], 401);
    }catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
          return response()->json(['error' => 'Token is Expired'], 401);
    }catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['error' => 'Token not provided'], 401);
    }
    
    return response()->json([
        'message' => 'Login Successfully',
        'user' => $user,
        'message' => 'Welcome to dashboard'
    ]);
}

public function logout(Request $request)
{
    try {
        $token = JWTAuth::getToken();
        if (!$token) {
            return response()->json(['error' => 'Token not Provided'], 401);
        }

        JWTAuth::invalidate($token);

        return response()->json(['message' => 'Log Out Successfully'], 200);

    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['error' => 'Failed to Logout'], 500);
    }
}


}
