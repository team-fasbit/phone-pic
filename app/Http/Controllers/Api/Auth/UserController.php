<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Auth;
use Log;
use DB;
use Hash;
use Mail;
use App\Mail\ForgotPassword;
class UserController extends Controller
{
    /*
    * public function check user registered or not
    */


    public function signup(Request $request)
    {
        $rules = [
                //'name'      => 'required|string|max:255',
                //'role'      => 'required',
                'email'     => 'required|string|email|max:255|unique:users',
                'password'  => 'required|min:6|confirmed',
            ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['message'] = $validator->messages();
            return $response;
        }
        $user = new User;
        $user->name= "user".rand(0,999999);
        $user->email = $request->email;
        $user->password = bcrypt($request->get('password'));
        $user->role = 2;
        if($user->save()) {
            $response['user']  = $user;
            $token = $user->createToken($user->id.' token')->accessToken;
            $response['access_token'] = $token;
            $response['success']  = true;
            $response['message'] =  'User has been registered successfully.'; 
            return $response; 
        }
    }
   

    public function login(Request $request){

        $rules = [
                    //'role'      => 'required',
                    'email'     => 'required',
                    'password'  => 'required'
                ];
         $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $response['success']  = false;
            $response['response'] = $validator->messages();
            return $response;
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
          
            $user =  Auth::user();
            $token = $user->createToken($user->id . ' token ')->accessToken;
            $result['message'] = "successfully login!";
            $result['access_token'] = $token;
            $result['user'] = $user;
            $result['success'] = true;
            return $result;
        }
        else{
            $result['success'] = false;
            $result['message'] = "These credentials do not match our records.!";
            return $result;
        }
    }


    public function forgotpassword(Request $request){
        $rules = [
                    'email'     => 'required|string|email'
                ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['message'] = $validator->messages();
            return $response;
        }
        try {
            $user  = new User;
            $six_digit_random_number = mt_rand(100000, 999999);
            $udetail = User::where('email', $request->get('email'))->first();
            if($udetail){
                $udetail->password = bcrypt($six_digit_random_number);
                $udetail->save();
                $mail = Mail::to($request->get('email'))->send(new ForgotPassword($udetail->name, $six_digit_random_number));
                $response['message'] = 'We have sent a new password on your email.';
                $response['success'] = true;
            }else{
                $response['message'] = 'This email id not registerd with us.';
                $response['success'] = false;
            }
            
        }
        catch (\Exception $e) {
            $response = [
                'error' => $e->getMessage() . ' Line No ' . $e->getLine() . ' in File' . $e->getFile()
            ];
            $response['success'] = false;
            Log::error($e->getTraceAsString());
        }

        return $response;
    }
    public function forgot(Request $request){
        $rules = [
                    'email'     => 'required|string|email'
                ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['message'] = $validator->messages();
            return $response;
        }
        try {
            $user  = new User;
            $code = rand(1000, 9999);
            $udetail = User::where('email', $request->get('email'))->first();
            if($udetail){
            	$udetail->four_digit_code = $code;
            	$udetail->save();

                $mail = Mail::to($request->get('email'))->send(new ForgotPassword($udetail->name, $code));
                $response['message'] = 'We have sent a four digit code on your email.';
                $response['four_digit_code'] = $code;
                $response['success'] = true;
            }else{
                $response['message'] = 'This email id not registerd with us.';
                $response['success'] = false;
            }
            
        }
        catch (\Exception $e) {
            $response = [
                'error' => $e->getMessage() . ' Line No ' . $e->getLine() . ' in File' . $e->getFile()
            ];
            $response['success'] = false;
            Log::error($e->getTraceAsString());
        }

        return $response;
    }

    public function resetPassword(Request $request){
       	
        $rules = [
                    'four_digit_code'  => 'required',
                    'new_password'     => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ];
        
       	$new_password = $request->get('new_password');
        $confirm_password = $request->get('confirm_password');
        $four_digit_code = $request->four_digit_code;

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['response'] = $validator->messages();
            return $response;
        }

        $password = bcrypt($new_password);
        $user = User::where('four_digit_code', $four_digit_code)->first();
        if($user){
        	$user->password = $password;
        	$user->four_digit_code = null;
        	$user->save();

            $response['success']  = true;
            $response['response'] = 'Password has been reset successfully.';
            return $response;
        }else{
        	$response['success']  = false;
            $response['response'] = 'Four digit code is invalid.';
            return $response;
        }  
    }
    

    public function facebookLogin(Request $request){
        $rules = [
                    
                    'role'  => 'required',
                    'name'  => 'required',
                    'email' => 'required|string|email',
                    'facebook_id' => 'required'
                ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['response'] = $validator->messages();
            return $response;
        }

        $check_facebook_id = User::where('facebook_id', $request->facebook_id)->first();
        if($check_facebook_id){
            $user = User::where('id', $check_facebook_id->id)->first();
            $response['success']  = true;
            $response['user']  = $user;
            $token = $user->createToken($user->id.' token')->accessToken;
            $response['access_token'] = $token;
            return $response;
        }
        $check_email_id = User::where('email', $request->email)->first();
        if($check_email_id){ 
            $user = User::where('id', $check_email_id->id)->first();
            $response['success']  = true;
            $response['user']  = $user;
            $token = $user->createToken($user->id.' token')->accessToken;
            $response['access_token'] = $token;
            return $response; 
        }  
        $user = new User;
        $user->name= $request->name;
        $user->last_name= $request->last_name;
        if($request->has('age')){
            $user->age = $request->age;
        }

        if($request->has('location')){
            $user->location = $request->location;
        }
        $user->email = $request->email;
        $user->password = '';
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->facebook_id = $request->facebook_id;

        if($user->save()){
            $response['success']  = true;
            $result['message'] = "successfully login!";
            $user = User::where('id', $user->id)->first();
            $response['user']  = $user;
            $token = $user->createToken($user->id.' token')->accessToken;
            $response['access_token'] = $token;
        }else{
            $response['success']  = false;
            $response['message'] =  'login failed.';

        }
        return $response;
    }

    public function getProfile(){
        $data = Auth::user();
        $response['success'] = true;
        $response['user'] =  $data;
        return $response;
    }
}
