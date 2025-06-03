<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Auth\EloquentUserProvider; 
use Illuminate\Validation\Rules\Password;


class UserController extends Controller
{

    public function index()
    {
        return User::all();
    }

    public function register(Request $request){
      
        $fields= Validator::make($request->all(),[
          'name' => 'required|string|max:255',
          'email'=> 'required|string|email',
          'birthday'=> 'required',
          'phone_number'=> 'required|max:9|min:9',
          'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            
        ]);
        if($fields->fails()){
            return response()->json($fields->errors());
         //  return response()->json("failed");
        }
        try {
            $user= User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'birthday'=> $request->birthday,
                'phone_number'=> $request->phone_number,
                'password'=> Hash::make($request->password)
            ]);
        
        //  $token= $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
          //   'acess_token'=> $token,
             'user'=>$user
            ]);

        } catch (\Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()]);
          
        }     
    }
    public function login(Request $request){
   
        $fields= Validator::make($request->all(),[
            'email'=> 'required|string|email',
            'password'=> 'required|string'
              
          ]);
          if($fields->fails()){
              return response()->json($fields->errors());
          }
          $cradentials= ['email'=> $request->email, 'password'=> $request->password];

          try {
            if(!auth()->attempt($cradentials)){
                return response()->json(['error'=> 'email or password is incorrect']);
            }
            $user= User::where('email', $request->email)->firstOrFail();
            $token= $user->createToken('api')->plainTextToken;
            return response()->json([
                'acess_token'=> $token,
                'user'=>$user
               ]);


          } catch (\Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()]);
          }

    }

    public function logout(Request $request){
      $request->user()->currentAccessToken()->delete();
      return response()->json([
        'message'=> 'user has logged out ',
       ]);
    }

    public function sendOtp(Request $request)
    {
        $user = Auth::user();

         $fields= Validator::make($request->all(),[
            'email'=> 'required|exists:users,email',
          ]);

          if($fields->fails()){
              return response()->json($fields->errors());
          }
    //dd($request->email);
    $email= $request->email;

    $otp = rand(100000, 999999);

    try {
       // Store OTP in session or database with expiry time
    Otp::where('email', $request->email)->delete();

    // Save new OTP
    Otp::create([
        'email' => $request->email,
        'otp' => $otp,
        'expires_at' => now()->addMinutes(5),
    ]);

    Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
    $message->to($email)
            ->subject('Your OTP Code');
});

    return response()->json(['message' => 'OTP sent successfully']); 

    } catch (\Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()]);
           }
    
    
}

public function resetPssword(Request $request)
{
     $fields= Validator::make($request->all(),[

            'email'=> 'required|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
          ]);

          if($fields->fails()){
              return response()->json($fields->errors());
          }

    $otpRecord = Otp::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->where('expires_at', '>', now())
                    ->first();

    if (!$otpRecord) {

     return response()->json(['message' => 'Invalid or expired OTP'], 400);

    }

   // Update password
   try {

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();
       // Optionally delete the OTP after use
        $otpRecord->delete();

        return response()->json(['message' => 'password reset successful']);

   }      catch (\Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()]);
           }
    
    
    }



}
