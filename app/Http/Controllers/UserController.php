<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function loadRegister(){

        if(Auth::check()){
            return view ('home');
           }
        return view('register');
    }
   
    public function userRegister(Request $request)
    {
        $request->validate([
         
            'name' => 'string|required|min:1',
            'email' => 'string|required|email|max:100|unique:users',
            'password' => 'string|required|min:6|confirmed',
        ]);

       $user = new User;
       $user->name= $request->name;
       $user->email= $request->email; 
       $user->password = Hash::make($request->password);
       $user->save();

       return back()->with('success', 'your Registration has been seccessfull');
    }

    public function loadLogin()
    {
        if(Auth::check()){
            return view ('home');
           }
        
        return view('login');
    }

    public function userLogin(Request $request)
    {
        $request->validate([
           'email' => 'string|required|email',
            'password' => 'string|required',
        ]);

        $userCredential = $request->only('email','password');

        if(Auth::attempt($userCredential))
        {
            return redirect('/home');
        }
        else{
            return back()-with('error', 'Username & Password is incorrect');
        }

    }

    public function home()
    {
       if(Auth::check()){
        return view ('home');
       }
       else{
        return redirect('/');  
       }
    }

    public function logout(Request $request)
     
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }

    public function forgotPassword(){
        return view('forget');
    }

    public function processForgetPassword(Request $request){
$validator = Validator::make($request->all(),[
    'email' => 'required|email|exists:users,email'
]);

 if ( $validator -> fails()){

    return redirect()->route('forget')->withInput()->withErrors($validator);
 }
  
 $token = str::random(60);

 DB::table('password_reset_tokens')->where('email',$request->email)->delete();

 DB::table('password_reset_tokens')->insert([

    'email' => $request->email,
    'token' => $token,
    'created_at' =>now()

 ]);

 //Send email

 $user = User::where('email',$request->email)->first();
 $formData =[
  'token' => $token,
  'user' => $user,
   'mailSubject' => 'You have requestes to reset password'
 ];

 Mail::to($request->email)->send(new ResetPasswordEmail($formData));

 return redirect()->route('forget')->with('success','please check your inbox to reset you password');

    }

    public function resetPassword($token){

        $tokenExist =DB::table('password_reset_tokens')->where('token',$token)->first();

        if($tokenExist == null) {

            return redirect()->route('forget_password')->with('error','Invalid request');
        }

        return view('reset-password',[
            'token' => $token
        ]);
    }

    public function processResetPassword(Request $request){

        $token = $request->token;

        $tokenObj =DB::table('password_reset_tokens')->where('token',$token)->first();

        if($tokenObj == null) {

            return redirect()->route('forget')->with('error','Invalid request');
        }

    $user = User::where('email',$tokenObj->email)->first();

    $validator = Validator::make($request->all(),[
        'new_password' => 'required|min:5',
        'confirm_password' =>  'required|same:new_password'

    ]);
    
     if ( $validator -> fails()){
    
        return redirect()->route('resetPassword',$token)->withErrors($validator);
     } 
     User::where('id',$user->id)->update([
        'password'=>Hash::make($request->new_password)

     ]);
   


     DB::table('password_reset_tokens')->where('email',$user->email)->delete();

     return redirect()->route('userLogin')->with('success','You have successfully update password');

    }
}
