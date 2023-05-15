<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\AuthVerifyAdmin;
// use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Http\Request;
// use Illuminate\Foundation\Auth\ResetsPasswords;
class AuthAdminController extends Controller
{
    use AuthVerifyAdmin;
    // use ResetsPasswords;
    // use CanResetPassword;
    public function __construct(){
        // $this->resetP;
        
        $this->middleware('auth:api', ['except' => ['login','register','registerTecherOrAdmin','createTeacherTemp']]);

    }


    public function register(Request $request){
        $userType = 0;
        $checkRegister = $this->checkIfAdminRegsterBefore($request);
         if(isset($checkRegister))
         return $checkRegister;
          
      $verifiedUser = $this->registerVerify($request);
      $user =null;
      if(isset($verifiedUser))
            {
              $colloge = $this->getColloge($verifiedUser);
              $section = $this->getSection($verifiedUser);
              if(!isset($colloge)){
                 $this->setColloge($verifiedUser);
                 $colloge = $this->getColloge($verifiedUser);
                 $userType=4;
                 $user = User::create([
                    'name' => $verifiedUser->name,
                    'email' => $request->email,
                    'colloge_id' => $colloge->id,
                    'id_number' => $verifiedUser->id_number ,
                    'password' => $verifiedUser->password,
                    'type'=> $userType,
                ]);
              }
              if(!isset($section)){
                 $this->setSection($verifiedUser,$colloge->id);
                 $section = $this->getSection($verifiedUser);
                 $userType=3;
                 $user = User::create([
                    'name' => $verifiedUser->name,
                    'email' => $request->email,
                    'section_id' => $section->id,
                    'id_number' => $verifiedUser->id_number ,
                    'password' => $verifiedUser->password,
                    'type'=> $userType,
                ]);

              }
             $user = $this->getUser($user);
             // $token = Auth::attempt($credentials);
             $token = auth('api')->login($user);
             return response()->json([
                 'status' => 'success',
                 'message' => 'User created successfully',
                 'user' => $user ,
                 'authorisation' => [
                     'token' => $token,
                     'type' => 'bearer',
                 ]
             ]);
            }
            else {
             return 
             response()->json([
                 'status' => 'error',
                 'message' => 'User not created  ',
                 'user' => null,
                 'authorisation' => [
                     'token' => null,
                     'type' => 'bearer',
                 ],200
             ]);
            }
         }
         private function getUser($user){
            return User::where('id', $user->id)
           ->with(['colloge'=> function ($colloge){
               $colloge->select('id','name');
           }])
           ->with(['section'=> function ($section){
               $section->select('id','name');
           }])
           ->first();
       }
 
 

}
