<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::all();
        foreach ($questions as $question) {
            $question->view_question = [
                'href' => 'api/v1/question/' . $question ->id,
                'method' => 'GET'
            ];
        }
        

        $response = [
            'msg' => 'List of all question',
            'question' => $questions
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
            'title' => 'required',
            'category' => 'required',
            'description' => 'required',
            'screenshot' => 'required',
        ]);


        $phone = $request-> input('phone');
        $title = $request-> input('title');
        $category = $request-> input('category');
        $description = $request-> input('description');
        $screenshot = $request-> input('screenshot');
        
       $question = new Question([
           'phone' => $phone,
           'title' => $title,
           'category' => $category,
           'description' => $description,
           'screenshot' => $screenshot,
       ]);

       if ($question->save()) {
           $question->view_question = [
               'href' => 'api/v1/question/' . $question->id,
               'method' => 'GET'
           ];

           $message = [
               'msg' => 'question Created',
               'question' => $question
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        if (!$question->delete()) {
            return response()->json(['msg' => 'Deletion Failed'], 404);
        }
        $response = [
            'msg' => 'question Deleted',
            'create' => [
                'href' => 'api/v1/question',
                'method' => 'POST',
                'params' => 'title, description, time'
            ]
            ];
        return response()->json($response, 200);
    }
}
