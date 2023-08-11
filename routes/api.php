<?php

use App\Http\Controllers\VerificationController;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//------------------ EMAIL VERIFICATION ------------------------------
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

//-------------------------- PASSWORD RESET---------------------------------
Route::post('/password/email', [VerificationController::class, 'sendemail']);
Route::get('/password/reset/{token}', function($token){
    return redirect()->away(env('FRONT_URL').'?token='.$token);
})->name('password.reset');
Route::post('/password/reset', [VerificationController::class, 'resetpassword']);

//----------------------- VERSION 1-------------------------------

Route::prefix('v1')->namespace('App\Http\Controllers\V1')->group(function(){
    Route::get('/', function(){
        return new UserCollection(User::all());
    });
    //AuthController
    Route::controller(AuthController::class)->group(function(){
        Route::middleware('guest')->group(function(){
            Route::post('/login', 'login');
            Route::post('/register', 'register');
        });
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });
    Route::middleware('auth:sanctum')->group(function(){
        Route::apiResources([
            'categories' => CategoryController::class,
            'quizzes' => QuizController::class,
        ]);
    });
});
