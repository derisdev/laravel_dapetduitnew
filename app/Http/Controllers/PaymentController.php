<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;


class PaymentController extends Controller
{

    public function __construct() {
        $this->middleware(
            'jwt.auth',[
                'except' => ['index', 'show', 'update', 'readForUser']
            ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();
        foreach ($payments as $payment) {
            $payment->view_payment = [
                'href' => 'api/v1/payment/' . $payment ->id,
                'method' => 'GET'
            ];
        }
        

        $response = [
            'msg' => 'List of all payment',
            'payment' => $payments
        ];
        
        return response()->json($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'via' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'time' => 'required',
            'user_id' => 'required',
        ]);


        $phone = $request-> input('phone');
        $via = $request-> input('via');
        $amount = $request-> input('amount');
        $status = $request-> input('status');
        $time = $request-> input('time');
        $user_id = $request-> input('user_id');
        
       $payment = new Payment([
           'phone' => $phone,
           'via' => $via,
           'amount' => $amount,
           'status' => $status,
           'time' => $time,
       ]);

       if ($payment->save()) {
           $payment->users()->attach($user_id);
           $payment->view_payment = [
               'href' => 'api/v1/payment/' . $payment->id,
               'method' => 'GET'
           ];

           $message = [
               'msg' => 'payment Created',
               'payment' => $payment
           ];
           return response()->json($message, 201);
       }

       $message = [
           'msg' => 'Error During Creating'
       ];
       return response()->json($message, 404);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::with('users')->where('id', $id)->firstOrFail();

        $payment->view_payments = [
            'href' => 'api/v1/payment',
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'Payment Information',
            'payment' => $payment
        ];

        return response()->json($response, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);


        $status = $request-> input('status');


        $payment = Payment::with('users')->findOrFail($id);

        if (!Payment::where('id', $id)->update([
            'status' => $status
            ])) {
            return response()->json(['msg' => 'Error During Update'], 404);
        }

        $payment->view_payment = [
            'href' => 'api/v1/payment/' . $payment->id,
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'Payment Updated',
            'payment' => $payment
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $users = $payment->users;
        $payment->users()->detach();

        if (!$payment->delete()) {
            foreach($users as $user) {
                $payment->users()->attach($user);
            }

            return response()->json(['msg' => 'Deletion Failed'], 404);
        }

        $response = [
            'msg' => 'Payment Deleted',
            'create' => [
                'href' => 'api/v1/payment',
                'method' => 'POST',
                'params' => 'title, description, time'
            ]
            ];
        return response()->json($response, 200);
    }


    public function readForUser($phone)
    {

        $payments = Payment::where('phone', $phone)->get()->all();
        
        $response = [
            'msg' => 'List of all payment',
            'payment' => $payments
        ];
        
        return response()->json($response, 200);
    }
}
