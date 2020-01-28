<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Phone;

class PhoneController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required',
            'user_id' => 'required',
        ]);

        $user = User::find($data['user_id']);

        
        $phone = new Phone([
              'phone' => $data['phone'],
          ]);
   
          if ($user->phone()->save($phone)) {
              $phone->view_phone = [
                  'href' => 'api/v1/user/phone/',
                  'method' => 'GET'
              ];
   
              $message = [
                  'msg' => 'Phone created',
                  'profile' => $phone
              ];
              return response()->json($message, 200);
          }
   
          $message = [
              'msg' => 'Error During Creating'
          ];
          return response()->json($message, 404);
    

    }



}
