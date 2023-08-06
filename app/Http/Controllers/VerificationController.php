<?php

namespace App\Http\Controllers;

use App\Mail\InstructorRegistration;
use App\Mail\LearnerRegistration;
use App\Mail\ParentRegistration;
use App\Mail\Welcome;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class VerificationController extends BaseController
{
    /** Verify user email
     * 
     */
    public function verify($user_id, Request $request) 
    {
        if (!$request->hasValidSignature()) {
            return $this->sendError("Invalid/Expired url provided.", null, 401);
        }
    
        $user = User::findOrFail($user_id);
    
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            //send registration mail
            Mail::to($user->email)->send(new Welcome($user));
        }
    
        return $this->sendResponse('Email verified successfully');
        return redirect()->away(env('FRONT_URL'));

    }
    
    /**
     * Resend verification email
     */
    public function resend() 
    {
        $user = User::find(auth()->user()->id);
        if ($user->hasVerifiedEmail()) {
            return $this->sendError("Email already verified.", null, 400);
        }
    
        $user->sendEmailVerificationNotification();
    
        return $this->sendResponse(null, "Email verification link sent to your email");
    }

    /**
     * Send password reset mail
     */
    public function sendEmail(Request $request)
    {
        $request->validate(['email' =>['required','email']]);
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
                    ? $this->sendResponse(__($status))
                    : $this->sendError(__($status));
    }

    /**
     * Reset password
     */

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' =>'required',
            'email' =>['required','email'],
            'password' => ['required', 'min:8','confirmed'],
            ]);
    
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));
    
                    $user->save();
                    event(new PasswordReset($user));
                }
    
            );
        return $status == Password::PASSWORD_RESET
                    ? $this->sendResponse(__($status))
                    : $this->sendError(__($status));
     }
}
