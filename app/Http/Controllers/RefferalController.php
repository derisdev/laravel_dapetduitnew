<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use App\Refferal; 

class RefferalController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'refferal' => 'required',
            'user_id' => 'required',
        ]);

        $user = User::find($data['user_id']);

        
        $refferal = new Refferal([
              'refferal' => $data['refferal'],
          ]);
   
          if ($user->refferal()->save($refferal)) {
              $refferal->view_refferal = [
                  'href' => 'api/v1/user/refferal/',
                  'method' => 'GET'
              ];
   
              $message = [
                  'msg' => 'refferal created',
                  'profile' => $refferal
              ];
              return response()->json($message, 200);
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
        $refferal = Refferal::where('id', $id)->firstOrFail();

        $refferal->view_refferals = [
            'href' => 'api/v1/refferal',
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'refferal Information',
            'refferal' => $refferal
        ];

        return response()->json($response, 200);
    }


}
