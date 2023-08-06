<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class AuthController extends BaseController
{
    /**
     * Registration of user
     * 
     */

    public function register(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        if($user){
            //create token
            $token = $user->createToken('token', [$user->role]);
            $user->api_token = $token->plainTextToken;
            $user->save();
            //send verification email
            $user->sendEmailVerificationNotification();
            return $this->sendResponse('Registration successful');
            return redirect()->away(env('FRONT_URL'));


        }
        return $this->sendError('Error in creating user.');
    }

    /**
     * Login
     * 
     */
    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',

        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = User::find(Auth::user()->id);
            $token = $user->createToken('token', ['admin']);
            $user->api_token = $token->plainTextToken;
            $user->save();
            return $this->sendResponse(new UserResource($user), 'Login Successful');
        }
        else{
            return $this->sendError('Credentials not found');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        request()->user()->tokens()->delete();
        return $this->sendResponse(null,'Successfully logged out');
    }

    /**
     * Get the profile details of the user
    */
    public function profile(Request $request)
    {
        return $this->sendResponse(new UserResource(auth()->user()));
    }

}
