<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\V1\AnswerResource;
use App\Http\Resources\V1\QuizResource;
use App\Http\Resources\V1\SubmissionResource;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubmissionController extends BaseController
{
    public function quizzes()
    {
        return $this->sendResponse(QuizResource::collection(Quiz::published()->get()));

    }

    public function quiz(Quiz $quiz)
    {
        return $this->sendResponse(new QuizResource($quiz->load('questions')->loadCount('questions')));

    }

    public function startQuiz(Quiz $quiz){
        $this->authorize('create', Submission::class);

        try {

            //check if there's an already ongoing submission for that quiz
    
            $check = Submission::where('user_id', request()->user()->id)->where('quiz_id', $quiz->id)
                                ->whereNull('end')->first();
            if($check){
                return $this->sendError('There is already an ongoing quiz');
            }
            else{
                $submission = Submission::create([
                    'user_id' => request()->user()->id,
                    'quiz_id' => $quiz->id,
                    'start' => Carbon::now()->toDateTimeString()
                ]);
                if($submission){
                    return $this->sendResponse(new SubmissionResource($submission), 'Quiz started');
                }
            }
        } catch (\Throwable $th) {
            $this->sendError('Error starting quiz', $th->getMessage());
        }

    }

    public function endQuiz(Quiz $quiz, Submission $submission){
        $this->authorize('update', $submission);
        $submission->end = Carbon::now()->toDateTimeString();
        if($submission->save()){
            return $this->sendResponse(new SubmissionResource($submission), 'Quiz ended');
        }else{
            return $this->sendError('Error stopping quiz');
        }
    }

    public function submit(Submission $submission, Request $request){
     
        $this->authorize('update', $submission);

        $request->validate([
            'question' => ['required', function($attribute, $values, $fail) use ($submission){
                $quiz = $submission->quiz;
                if(!in_array($values, $quiz->questions()->pluck('id')->toArray())){
                    $fail('The question should be part of the quiz.');
                }
                }],
            'answer' => ['required'],
        ]);

        //first check is submission is ongoing
        if($submission->end == null){
            //get the answer of the question
            $question = Question::find($request->question);
            $ans = $question->questionable->answer;
            //check if there's an answer for the question already
            //if there is change the answer
            $answer = $submission->answers()->where('question_id', $request->question)->first();
            if($answer){
                $answer->answer = $request->answer;
                if($ans === $request->answer){
                    $answer->is_correct = 1;
                }else{
                    $answer->is_correct = 0;
                }
                $answer->save();
            }else{
                $answer = $submission->answers()->create([
                    'question_id' => $request->question,
                    'answer' => $request->answer,
                    'is_correct' => $ans === $request->answer ? 1 : 0
                ]);
            }
            //save the score
            $score = $submission->answers()->where('is_correct', 1)->count();
            $submission->score = $score;
            $submission->save();
            return $this->sendResponse(new  AnswerResource($answer));

        }
        else{
            return $this->sendError('The quiz is not ongoing');
        }
    }

    public function submissions(Quiz $quiz){
        $submissions = Submission::where('quiz_id', $quiz->id)->get();
        return $this->sendResponse(SubmissionResource::collection($submissions));
    }

    /**
     * Return details of a submission
     */
    public function submission(Quiz $quiz, Submission $submission){
        $this->authorize('update', $submission);
        return $this->sendResponse(new SubmissionResource($submission->load('answers')->loadCount('answers')));
    }
}
