<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {   	 

       try {
       		 request()->validate([
	            'name' => 'required',
	            'email' => 'required',
	        ]);

	            $request->merge([
	                'password' => Hash::make('test'),
	            ]);

	        User::create($request->all());

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
       } catch (Exception $e) {
       		throw $e;
       }
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {

        try {
                $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);

            $credentials = request(['email', 'password']);


            /*authentication Faillure*/
            if(!Auth::attempt($credentials)){            

                throw new Exception('Username or Password you entered is incorrect.',401);
            }

            /*Auth Success*/
            $user = $request->user();


            /*Token Creations*/
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response()->json([
                'status' => 200,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'user_data' => $user
            ],200);         

        } catch (Exception $e) {
            $this->postLogs(config('errorcontants.auth'), $e);
            throw $e;
        }
        
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        
         return response()->json([
            'status' => 200,
            'msg' => 'Successfully logged out'
        ],200);
    }
}
