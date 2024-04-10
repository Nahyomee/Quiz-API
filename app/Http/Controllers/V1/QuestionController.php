<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\V1\StoreQuestionRequest;
use App\Http\Requests\V1\UpdateQuestionRequest;
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
     * Get all questions of a quiz
     */
    public function index(Quiz $quiz)
    {
        return $this->sendResponse(QuestionTypeResource::collection($quiz->questions));
    }

    /**
     * Add a question to a quiz.
     */
    public function store(StoreQuestionRequest $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);
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
     * Show a question.
     */
    public function show(Quiz $quiz, Question $question)
    {
        return $this->sendResponse(new QuestionTypeResource($question));
    }

    /**
     * Update a question
     */
    public function update(UpdateQuestionRequest $request, Quiz $quiz, Question $question)
    {
        $this->authorize('update', $quiz);
        //only change the question and the answer
        try {
        
            $quest = $question->questionable;
            $quest->update($request->all());

            return $this->sendResponse(new QuestionTypeResource($question), 'Question updated');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating question', $th->getMessage());

        }
    }

    /**
     * Delete a question from a quiz.
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        $this->authorize('delete', $quiz);
        try {
            if($question->questionable instanceof ChoiceQuestion){
                $question->questionable->options()->delete();
            }
            $question->questionable->delete();
            if($question->delete()){
                return $this->sendResponse(null, 'Question deleted');
            }
            return $this->sendError('Error deleting question');
            
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error deleting question', $th->getMessage());
        }

    }
}
