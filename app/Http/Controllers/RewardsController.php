<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Rewards;

class RewardsController extends Controller
{

    public function __construct() {
        $this->middleware(
            'jwt.auth',[
                'except' => ['index', 'show']
            ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'rewards' => 'required',
            'user_id' => 'required',
        ]);

        $user = User::find($data['user_id']);

        
        $rewards = new Rewards([
              'rewards' => $data['rewards'],
          ]);
   
          if ($user->rewards()->save($rewards)) {
              $rewards->view_rewards = [
                  'href' => 'api/v1/user/rewards/',
                  'method' => 'GET'
              ];
   
              $message = [
                  'msg' => 'rewards created',
                  'profile' => $rewards
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
        $rewards = Rewards::where('id', $id)->firstOrFail();

        $response = [
            'msg' => 'rewards Information',
            'rewards' => $rewards
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
            'rewards' => 'required',
        ]);


        $rewards = $request-> input('rewards');

        $rewards_class = Rewards::findOrFail($id);

        $recent_rewards = Rewards::where('id', $id)->value('rewards');

        $total_rewards = $recent_rewards+=$rewards;


        if (!Rewards::where('id', $id)->update([
            'rewards' => $total_rewards
            ])) {
            return response()->json(['msg' => 'Error During Update'], 404);
        }

        $response = [
            'msg' => 'Rewards Updated',
            'rewards' => $total_rewards
        ];

        return response()->json($response, 200);
    }

}
