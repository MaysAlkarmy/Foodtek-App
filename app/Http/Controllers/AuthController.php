<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Auth\EloquentUserProvider; 
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller
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

    public function sendResetLinkEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'البريد الإلكتروني غير موجود'], 404);
    }
    // توليد رمز تحقق عشوائي
    $otp = rand(100000, 999999);
    // تخزينه في قاعدة البيانات (مثلاً في عمود otp)
    $user->otp = $otp;
    $user->save();
    // إرسال الرمز إلى الإيميل
    Mail::raw("رمز التحقق الخاص بك هو: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('رمز التحقق لإعادة تعيين كلمة المرور');
    });

    return response()->json(['message' => 'تم إرسال رمز التحقق إلى البريد الإلكتروني']);
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required'
    ]);

    $user = User::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

    if (!$user) {
        return response()->json(['message' => 'رمز التحقق غير صحيح'], 400);
    }

    return response()->json(['message' => 'تم التحقق من الرمز بنجاح']);
}

public function resetPassword(Request $request)
    { $request->validate([
        'email' => 'required|email',
        'otp' => 'required',
        'new_password' => 'required|min:6'
    ]);

    $user = User::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

    if (!$user) {
        return response()->json(['message' => 'البيانات غير صحيحة'], 400);
    }

    $user->password = Hash::make($request->new_password);
    $user->otp = null; // حذف الرمز بعد الاستخدام
    $user->save();

    return response()->json(['message' => 'تمت إعادة تعيين كلمة المرور بنجاح']);;
    }

}
