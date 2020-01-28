<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; 
use App\Phone; 

use JWTAuth;
use JWTAuthException;


class AuthController extends Controller
{
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $name = $request->input('name');
        $refferal_code = $request->input('refferal_code');
        $email = $request->input('email');
        $password = $request->input('password');


        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        $cridentials = [
            'email' => $email,
            'password' => $password
        ];

        if($refferal_code!=null) {
            $refferal = Refferal::where('refferal_code', $refferal_code)->first();
            $rewards = $refferal->value('rewards');

        if ($refferal!=null) {
            if ($user->save()) {
                $token = null;
    
                try {
                    if(!$token = JWTAuth::Attempt($cridentials)) {
                        return response()->json([
                            'msg' => 'Email or Password incorrect',
                        ], 404);
                    }
                } catch (\JWTAuthException $e) {
                    return response()->json([
                        'msg' => 'failed_to_create_token',
                    ], 400);
                }
    
                $user->signin = [
                    'href' => 'api/v1/user/signin',
                    'method' => 'POST',
                    'params' => 'email, password'
                ];
    
                $response = [
                    'msg' => 'User Created',
                    'user' => $user,
                    'token' => $token,
                    'cek rewards refferer' => $rewards
                ];
            return response()->json($response, 201);
            }
        }

        $response = [
            'msg' => 'An error occured'
        ];

        return response()->json($response, 404);
        }

        else {
            if ($user->save()) {

                $token = null;
    
                try {
                    if(!$token = JWTAuth::Attempt($cridentials)) {
                        return response()->json([
                            'msg' => 'Email or Password incorrect',
                        ], 404);
                    }
                } catch (\JWTAuthException $e) {
                    return response()->json([
                        'msg' => 'failed_to_create_token',
                    ], 400);
                }
    
                $user->signin = [
                    'href' => 'api/v1/user/signin',
                    'method' => 'POST',
                    'params' => 'email, password'
                ];
    
                $response = [
                    'msg' => 'User Created',
                    'user' => $user,
                    'token' => $token
                ];
            return response()->json($response, 201);
            }
            $response = [
                'msg' => 'An error occured'
            ];
    
            return response()->json($response, 404);
        }
    }

    public function signin(Request $request) {
        $this->validate($request, [
            'phone' => 'required'
        ]);

        $phone_input = $request->input('phone');

        $email = $request->input('email');
        $password = $request->input('password');


        
        if ($phone = Phone::where('phone', $phone_input)->first()) {
            
        $cridentials = [
            'email' => $email,
            'password' => $password
        ];


        $token = null;

            try {
                if(!$token = JWTAuth::Attempt($cridentials)) {
                    return response()->json([
                        'msg' => 'Email or Password incorrect',
                    ], 404);
                }
            } catch (\JWTAuthException $e) {
                return response()->json([
                    'msg' => 'failed_to_create_token',
                ], 400);
            }

            $response = [
                'msg' => 'User Signin',
                'phone' => $phone,
                'token' => $token
            ];
        return response()->json($response, 201);
        }

        $response = [
            'msg' => 'An error occured'
        ];

        return response()->json($response, 404);
    }
}