<?php

namespace App\Http\Controllers;

use App\Http\Services\FatoorahServices;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $fatoorahServices;
    public function __construct(FatoorahServices $fatoorahServices)
    {
        $this->fatoorahServices = $fatoorahServices;
    }
    // auth section
    public function login(){
      $credentials = request(['email','password']);
      $auth = auth()->attempt($credentials);
      if (! $auth) {

          return response()->json(['msg'=>"something wrong in user information"],404);
      }
        $user = User::where('email' , request('email'))->first();
        $token = $user->createToken("myToken")->plainTextToken;
        $response= [
            'user'=>$user,
            'token'=>$token
        ];
       return response($response,201);
    }
    public function register(Request $request){
        $filed = $request->validate([
            'name'=>'required|string',
            'email' =>'required|string|email',
            'password' => 'required|string|confirmed'
        ]) ;
        $user= User::create([
            'name'=>$filed['name'],
            'email'=>$filed['email'],
            'password'=>Hash::make($filed['password']),
        ]);
        $token = $user->createToken("myToken")->plainTextToken;
        $response= [
            'user'=>$user,
            'token'=>$token
        ];
       return response($response,201);
    }
    public function info(Request $request){
        return Auth::user();
    }
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['msg'=>"logout success"],200);
    }
    /*                  END                         */

    /*                 Start                         */
    
    //payment section
    public function payorder(){
           $data = [
                "CustomerName"=>"name",
                "NotificationOption"=> "LNK",
                "CustomerEmail"=> "mail@company.com",
                "InvoiceValue"=> 100,
                "DisplayCurrencyIso"=> "SAR",
                "CallBackUrl"=>config("payment.CallBackUrl"),
                "ErrorUrl"=>config("payment.ErrorUrl"),
                "Language"=> "en",
          ];

        $res=  $this->fatoorahServices->sendPayment($data);
        return response()->json(['data' => $res]);


    }
    public function success(Request $request){
        $data=[
          'key'=>$request->paymentId,
          'keyType'=>'paymentId'
        ];
       return $this->fatoorahServices->getPaymentStatus($data);
    }

    public function error(Request $request){
           return response()->json(['errors'=>'something wrong ...']);
    }
}
