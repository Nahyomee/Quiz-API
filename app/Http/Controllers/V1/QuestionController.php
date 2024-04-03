<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\V1\StoreQuestionRequest;
use App\Http\Resources\V1\QuestionResource;
use App\Http\Resources\V1\QuestionTypeResource;
use App\Http\Resources\V1\QuizResource;
use App\Models\BlankQuestion;
use App\Models\ChoiceQuestion;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\TrueFalseQuestion;
use Illuminate\Http\Request;

class QuestionController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        //  dd($request->all());
          try{
              $quest = new Question;
              $quest->quiz_id = $quiz->id;
              if($request->type == 'multiple choice'){
                  $choice = ChoiceQuestion::create($request->except('options'));
                foreach($request->options as $option){
                    $choice->options()->create(['option' => $option]);
                }
                  $question = $choice->questions()->save($quest);
              }
              elseif($request->type == 'true false'){
                  $true_false = TrueFalseQuestion::create($request->all());
                  $question = $true_false->questions()->save($quest);

              }
              elseif($request->type == 'blank'){
                  //save all answers
                  $blank = BlankQuestion::create($request->all());
                  $question = $blank->questions()->save($quest);

              }
              if($question){
                  return $this->sendResponse(new QuestionTypeResource($question), 'Question added');
              }
              else{
              return $this->sendError('Error creating question');
              }
          }catch(\Throwable $th){
              return $this->sendError('Error creating question', $th->getMessage());
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
