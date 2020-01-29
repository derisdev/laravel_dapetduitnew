<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offerwall;

class OfferwallController extends Controller
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
        $offerwalls = Offerwall::all();
        foreach ($offerwalls as $offerwall) {
            $offerwall->view_offerwall = [
                'href' => 'api/v1/offerwall/' . $offerwall ->id,
                'method' => 'GET'
            ];
        }
        

        $response = [
            'msg' => 'List of all offerwall',
            'offerwall' => $offerwalls
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
            'icon' => 'required',
            'title' => 'required',
            'image' => 'required',
            'description' => 'required',
            'coin' => 'required',
        ]);


        $icon = $request-> input('icon');
        $title = $request-> input('title');
        $image = $request-> input('image');
        $description = $request-> input('description');
        $coin = $request-> input('coin');
        
       $offerwall = new Offerwall([
           'icon' => $icon,
           'title' => $title,
           'image' => $image,
           'description' => $description,
           'coin' => $coin,
       ]);

       if ($offerwall->save()) {
           $offerwall->view_offerwall = [
               'href' => 'api/v1/offerwall/' . $offerwall->id,
               'method' => 'GET'
           ];

           $message = [
               'msg' => 'offerwall Created',
               'offerwall' => $offerwall
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
            'icon' => 'required',
            'title' => 'required',
            'image' => 'required',
            'description' => 'required',
            'coin' => 'required',
        ]);


        $icon = $request-> input('icon');
        $title = $request-> input('title');
        $image = $request-> input('image');
        $description = $request-> input('description');
        $coin = $request-> input('coin');
        

        $offerwall = Offerwall::findOrFail($id);

        $offerwall->icon = $icon;
        $offerwall->title = $title;
        $offerwall->image = $image;
        $offerwall->description = $description;
        $offerwall->coin = $coin;


        if (!$offerwall->update()) {
            return response()->json(['msg' => 'Error During Update'], 404);
        }

        $offerwall->view_offerwall = [
            'href' => 'api/v1/offerwall/' . $offerwall->id,
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'offerwall Updated',
            'offerwall' => $offerwall
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
        $offerwall = Offerwall::findOrFail($id);

        if (!$offerwall->delete()) {
            return response()->json(['msg' => 'Deletion Failed'], 404);
        }
        $response = [
            'msg' => 'offerwall Deleted',
            'create' => [
                'href' => 'api/v1/offerwall',
                'method' => 'POST',
                'params' => 'title, description, time'
            ]
            ];
        return response()->json($response, 200);
    }
}
