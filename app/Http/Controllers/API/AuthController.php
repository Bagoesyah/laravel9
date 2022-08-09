<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);
        $token = $user->createToken("authToken")->plainTextToken;
        return response()->json([
            "message" => "Register Berhasil",
            "token_access"  => $token,
            "token_type" => "bearer",
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = User::where("email", $request->email)->first();
            $token = $user->createToken("authToken")->plainTextToken;
            return response()->json([
                "message" => "Login Berhasil",
                "token_access"  => $token,
                "token_type" => "bearer",
            ]);
        } else {
            return response()->json([
                "message" => "Login gagal",
            ]);
        }
    }

        /**
    * Register api
    *
    * @return \Illuminate\Http\Response
    */
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required',
    //         'c_password' => 'required|same:password',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json([
    //             "message" => "Validation Error.",
    //             $validator->errors()
    //         ]);
    //     }

    //     $input = $request->all();
    //     $input['password'] = bcrypt($input['password']);
    //     $user = User::create($input);
    //     $success['token'] =  $user->createToken('MyApp')->accessToken;
    //     $success['name'] =  $user->name;

    //     return response()->json([
    //         "message" => "Login Berhasil",
    //         "token_access"  => $success,
    //         ]);}

        /**
    * Login api
    *
    * @return \Illuminate\Http\Response
    */

    // public function login(Request $request)
    // {
    //     if(Auth::attempt($request->only('email', 'password'))){
    //         $user = Auth::user();
    //         $success['token'] =  $user->createToken('MyApp')-> accessToken;
    //         $success['name'] =  $user->name;

    //         return response()->json(['success' => $success], $this-> successStatus);

    //     } else {
    //         return response()->json(['error'=>'Unauthorised'], 401);
    //     }
    // }

}
