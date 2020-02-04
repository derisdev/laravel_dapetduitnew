<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; 
use App\Phone; 
use App\Refferal; 
use App\usertotal;  
use App\HistoryRewards;  
use App\Payment;  


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

        $usertotal =  new usertotal;
        $usertotal->total = 0;
        $usertotal->save();



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

            $recent_invited = Refferal::where('refferal', $refferal_input)->value('invited');
            $total_invited = $recent_invited+=1;


           if ($refferal_input!=null) {
            if (!Refferal::where('refferal', $refferal_input)->update([
                'invited' => $total_invited
                ])) {
                return response()->json([
                    'msg' => 'Error During Update, Refferal Not found',
                ], 404);
            }
            else {
                if ($user->save()) {

                    $current = usertotal::where('id', 1)->value('total');
                    usertotal::where('id', 1)->update([
                        'total' => $current+=1
                    ]);
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

           }
           else {
            if ($user->save()) {

                $current = usertotal::where('id', 1)->value('total');
                    usertotal::where('id', 1)->update([
                        'total' => $current+=1
                    ]);


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

    public function index() {
        $users = User::with('phone')->with('refferal')->with('rewards')->get();


        $response = [
            'msg' => 'List User',
            'user' => $users
        ];
        return response()->json($response, 200);


    }



    public function total() {
        $usertotal = usertotal::find(1);

        $response = [
            'msg' => 'User total',
            'usertotal' => $usertotal
        ];
        return response()->json($response, 200);


    }

    
    public function historyRewards($id) {
        $historyRewards = HistoryRewards::where('user_id', $id)->get();

        $response = [
            'msg' => 'List History Rewards',
            'user' => $historyRewards
        ];
        return response()->json($response, 200);


    }
    
    public function historyPayment($phone) {
        $historyPayment = Payment::where('phone', $phone)->get();

        $response = [
            'msg' => 'List History Payment',
            'user' => $historyPayment
        ];
        return response()->json($response, 200);


    }

}
