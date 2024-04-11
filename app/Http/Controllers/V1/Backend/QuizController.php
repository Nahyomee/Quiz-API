<?php

namespace App\Http\Controllers\V1\Backend;

use App\Http\Controllers\BaseController;
use App\Http\Requests\V1\StoreQuizRequest;
use App\Http\Requests\V1\UpdateQuizRequest;
use App\Http\Resources\V1\QuizCollection;
use App\Http\Resources\V1\QuizResource;
use App\Models\BlankQuestion;
use App\Models\ChoiceQuestion;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\TrueFalseQuestion;
use Illuminate\Http\Request;
USE Illuminate\Support\Str;

class QuizController extends BaseController
{

     /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Quiz::class, 'quiz');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(QuizResource::collection(request()->user()->quizzes));
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuizRequest $request)
    {
        try{
            //ADD USER ID AMD CAT ID TO QUIZ
            $quiz =$request->user()->quizzes()->create($request->except('category') + ['category_id' => $request->category, 'slug' => Str::slug($request->title)]);
            if($quiz){
                return $this->sendResponse(new QuizResource($quiz), 'Quiz created');
            }
            else{
                return $this->sendError('Error creating quiz');
            }
        }catch(\Throwable $th){
            return $this->sendError('Error creating quiz', $th->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        return $this->sendResponse(new QuizResource($quiz->load('questions')->loadCount('questions')));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        try{
            $quiz->update($request->except('category'));
            if($quiz->wasChanged('title')){
                $quiz->slug = Str::slug($request->title);
                $quiz->save();
            }
            if($request->category){
                $quiz->category_id = $request->category;
                $quiz->save();
            }
            return $this->sendResponse(new QuizResource($quiz), 'Quiz updated');
        }catch(\Throwable $th){
            return $this->sendError('Error updating quiz', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        try {
            //delete questions 
            $questions = $quiz->questions;
            foreach ($questions as $question) {
                //get the type  of question and delete
                if($question->questionable instanceof ChoiceQuestion){
                    $question->questionable->options()->delete();
                }
                $question->questionable->delete();
                $question->delete();
                //delete question
            }
            $quiz->delete();
        } catch (\Throwable $th) {
            return $this->sendError('Error in deleting quiz.', $th->getMessage());
        }
        return $this->sendResponse(null, 'Quiz deleted successfully');
    }

    /**
     * Publish a quiz
     */
    public function publishQuiz(Request $request){
        $quiz = Quiz::find($request->quiz);
        $this->authorize('update', $quiz);
        if($quiz->is_published){
            return $this->sendError('Quiz already published!');
        }
        $quiz->is_published = 1;
        if($quiz->save()){
            return $this->sendResponse(new QuizResource($quiz), 'Quiz published!');
        }else{
            return $this->sendError('Error in publishing quiz!');
        }
    }

     /**
     * Unpublish a quiz
     */
    public function unpublishQuiz(Request $request){
        $quiz = Quiz::find($request->quiz);
        $this->authorize('update', $quiz);
        if(!$quiz->is_published){
            return $this->sendError('Quiz is already unpublished!');
        }
        $quiz->is_published = 0;
        if($quiz->save()){
            return $this->sendResponse(new QuizResource($quiz), 'Quiz unpublished!');
        }else{
            return $this->sendError('Error in unpublishing quiz!');
        }
    }


}
