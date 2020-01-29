<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Feedback;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = Feedback::all();
        foreach ($feedbacks as $feedback) {
            $feedback->view_feedback = [
                'href' => 'api/v1/feedback/' . $feedback ->id,
                'method' => 'GET'
            ];
        }
        

        $response = [
            'msg' => 'List of all feedback',
            'feedback' => $feedbacks
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
            'question' => 'required',
            'answer' => 'required',
        ]);


        $question = $request-> input('question');
        $answer = $request-> input('answer');
        
       $feedback = new Feedback([
           'question' => $question,
           'answer' => $answer,
       ]);

       if ($feedback->save()) {
           $feedback->view_feedback = [
               'href' => 'api/v1/feedback/' . $feedback->id,
               'method' => 'GET'
           ];

           $message = [
               'msg' => 'feedback Created',
               'feedback' => $feedback
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
            'question' => 'required',
            'answer' => 'required',
        ]);


        $question = $request-> input('question');
        $answer = $request-> input('answer');
        

        $feedback = Feedback::findOrFail($id);

        $feedback->question = $question;
        $feedback->answer = $answer;


        if (!$feedback->update()) {
            return response()->json(['msg' => 'Error During Update'], 404);
        }

        $feedback->view_feedback = [
            'href' => 'api/v1/feedback/' . $feedback->id,
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'feedback Updated',
            'feedback' => $feedback
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
        $feedback = Feedback::findOrFail($id);

        if (!$feedback->delete()) {
            return response()->json(['msg' => 'Deletion Failed'], 404);
        }
        $response = [
            'msg' => 'feedback Deleted',
            'create' => [
                'href' => 'api/v1/feedback',
                'method' => 'POST',
                'params' => 'title, description, time'
            ]
            ];
        return response()->json($response, 200);
    }
}
