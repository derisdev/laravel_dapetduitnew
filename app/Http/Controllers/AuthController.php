<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; 
use App\Phone; 
use App\Refferal; 
use App\UserInfo; 

use JWTAuth;
use JWTAuthException;


class AuthController extends Controller
{
    public function __construct() {
        $this->middleware(
            'jwt.auth',[
                'only' => ['index']
            ]);
    }


    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $name = $request->input('name');
        $refferal_input = $request->input('refferal');
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

            $refferal = Refferal::where('refferal', $refferal_input)->first();
            $recent_invited = Refferal::where('refferal', $refferal_input)->value('invited');
            $total_invited = $recent_invited+=1;


           if ($refferal!=null) {
            if (!Refferal::where('refferal', $refferal_input)->update([
                'invited' => $total_invited
                ])) {
                return response()->json([
                    'msg' => 'Error During Update, Refferal Not found',
                ], 404);
            }

           }

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
                    'invited' => $total_invited
                ];
            return response()->json($response, 201);
            }

        $response = [
            'msg' => 'An error occured'
        ];

        return response()->json($response, 404);

        
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

    public function index()
    {
        $userinfos = UserInfo::all();
        foreach ($userinfos as $userinfo) {
            $userinfo->view_userinfo = [
                'href' => 'api/v1/userinfo/' . $userinfo ->id,
                'method' => 'GET'
            ];
        }
        

        $response = [
            'msg' => 'List of all userinfo',
            'userinfo' => $userinfos
        ];
        
        return response()->json($response, 200);
    }

}
