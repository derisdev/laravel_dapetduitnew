<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notif;

class NotifController extends Controller
{

    public function __construct() {
        $this->middleware(
            'jwt.auth',[
                'except' => ['index', 'show']
            ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifs = Notif::all();
        foreach ($notifs as $notif) {
            $notif->view_notif = [
                'href' => 'api/v1/notif/' . $notif ->id,
                'method' => 'GET'
            ];
        }
        

        $response = [
            'msg' => 'List of all notif',
            'notif' => $notifs
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
            'title' => 'required',
            'description' => 'required',
            'time' => 'required',
        ]);


        $title = $request-> input('title');
        $description = $request-> input('description');
        $time = $request-> input('time');
        
       $notif = new Notif([
           'title' => $title,
           'time' => $time,
           'description' => $description,
       ]);

       if ($notif->save()) {
           $notif->view_notif = [
               'href' => 'api/v1/notif/' . $notif->id,
               'method' => 'GET'
           ];

           $message = [
               'msg' => 'notif Created',
               'notif' => $notif
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
        //
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
            'title' => 'required',
            'time' => 'required',
            'description' => 'required',
        ]);


        $title = $request-> input('title');
        $time = $request-> input('time');
        $description = $request-> input('description');
        

        $notif = Notif::findOrFail($id);

        $notif->title = $title;
        $notif->time = $time;
        $notif->description = $description;


        if (!$notif->update()) {
            return response()->json(['msg' => 'Error During Update'], 404);
        }

        $notif->view_notif = [
            'href' => 'api/v1/notif/' . $notif->id,
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'notif Updated',
            'notif' => $notif
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
        $notif = Notif::findOrFail($id);

        if (!$notif->delete()) {
            return response()->json(['msg' => 'Deletion Failed'], 404);
        }
        $response = [
            'msg' => 'notif Deleted',
            'create' => [
                'href' => 'api/v1/notif',
                'method' => 'POST',
                'params' => 'title, description, time'
            ]
            ];
        return response()->json($response, 200);
    }
}
