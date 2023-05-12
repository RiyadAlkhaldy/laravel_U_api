<?php

namespace App\Http\Controllers;



use App\Models\TeacherTemp;
use App\Traits\AuthVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\AuthVerifyAdmin;

class AuthController extends Controller
{
use AuthVerify;
use AuthVerifyAdmin;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','registerTeacher','createTeacherTemp','registerAdmin']]);
    }

    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        // ]);
        $credentials = $request->only('email', 'password');

        $token = Auth('api')->attempt($credentials);
        // $token = Auth::attempt($credentials);
        // return $token;
         
        // $user = User::where( 'email' ,'=', $request->email)->where('password','=',Hash::make($request->password))->first();
        $user = Auth('api')->user();
        // return $user;

$user = $this->getUser($user);
        // return $user;

        if(isset($user)){
            // $user = $this->getUser($user);
            // // $token = Auth::attempt($credentials);
            // $token = auth('api')->login($user);
       
        // $token = auth('api')->login($user);

        // $user = Auth::user();
        return response()->json([ 
                'status' => 'success',
                'message' => 'User login successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
 }
 return response()->json([ 
    'status' => 'error',
    'message' => 'login not success',
    'user' =>null,
        'authorisation' => [
        'token' => null,
        'type' => 'bearer',
    ]
]);
    }

public function register(Request $request){
   $checkRegister = $this->checkIfUserRegsterBefore (  $request);
    if(isset($checkRegister))
    return $checkRegister;
     
 $verifiedUser = $this->registerVerify($request);

 if(isset($verifiedUser))
       {
         $colloge = $this->getColloge($verifiedUser);
         $section = $this->getSection($verifiedUser);
         if(!isset($colloge)){
            $this->setColloge($verifiedUser);
            $colloge = $this->getColloge($verifiedUser);
         }
         if(!isset($section)){
            $this->setSection($verifiedUser,$colloge->id);
            $section = $this->getSection($verifiedUser);
         }

        $user = User::create([
            'name' => $verifiedUser->name,
            'email' => $request->email,
            'colloge_id' => $colloge->id,
            'section_id' => $section->id,
            'university_id' => $verifiedUser->university_id,
            'id_number' => $verifiedUser->id_number ,
            'password' => Hash::make($verifiedUser->id_number),
            'type'=>$request->type,
        ]);
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
    
    public function registerTeacher(Request $request){
        $checkRegister = $this->checkIfAdminOrTecherRegsterBefore ($request);
        if(isset($checkRegister))
        return $checkRegister;

               $user = User::create([
                   'name' => $request->name,
                   'email' => $request->email,
                   'colloge_id' => $request->colloge_id,
                   'id_number' => $request->id_number ,
                   'password' => Hash::make($request->password),
                   'type'=>$request->type,
               ]);
               $user = $this->getUser($user);

               $token = auth('api')->login($user);
               return  response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user ,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
              }

/*
create teacher temp
  */
    public function createTeacherTemp(Request $request){
       $teacher = TeacherTemp::create([
            'name' => $request->name,
            'email' => $request->email,
            'colloge_id' => $request->colloge_id,
            'id_number' => $request->id_number ,
            'password' => Hash::make($request->password),
            'type'=>$request->type,
        ]);

        return response()->json(['techer'=> $teacher]);

    }
/*
create colloge or secetion
  */
    public function registerAdmin(Request $request){
        $userType = 0;
        $checkRegister = $this->checkIfAdminRegsterBefore($request);
         if(isset($checkRegister))
         return $checkRegister;
          
      $verifiedUser = $this->registerVerifyAdmin($request);
    //   return $verifiedUser;
      $user =null;
      if(isset($verifiedUser))
       {
         $colloge = $this->getColloge($verifiedUser);
         $section = $this->getSection($verifiedUser);
         if(!isset($colloge)){
            $this->setColloge($verifiedUser);
            $colloge = $this->getColloge($verifiedUser);
         }
         if(!isset($section)){
            $this->setSection($verifiedUser,$colloge->id);
            $section = $this->getSection($verifiedUser);
         }

        $user= $this->createUserAdmin($request,$verifiedUser,$colloge->id,$section->id);
            //   return auth('api')->attempt($user);
            // return $user;
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
                 'message' => 'User not created البيانات ليست صحيحة ',
                 'user' => null,
                 'authorisation' => [
                     'token' => null,
                     'type' => 'bearer',
                 ],200
             ]);
            }
         }

           /*
              create  User  admin as dean or presindate
           */
    private function createUserAdmin(Request $request,$verifiedUser,$colloge_id=null,$section_id=null){
        if($colloge_id != null){
             $user= User::create([
                'name' => $verifiedUser->name,
                'email' => $request->email,
                'colloge_id' => $colloge_id  ,
               //  'section_id' => $section_id??null,
                'id_number' => $request->id_number ,
                'password' => Hash::make($request->password),
                'type'=>$request->type,
            ]);
            return $user;
        }
       

         return User::create([
            'name' => $verifiedUser->name,
            'email' => $request->email,
            // 'colloge_id' => $colloge_id ,
            'section_id' => $section_id,
            'id_number' => $request->id_number ,
            'password' => Hash::make($request->password),
            'type'=>$request->type,
        ]);
     }
       
    public function logout()
    {
        Auth('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function me()
    {

        $user = User::where('id', Auth::user()->id)
        ->with(['colloge'=> function ($colloge){
            $colloge->select('id','name');
        }])
        ->with(['section'=> function ($section){
            $section->select('id','name');
        }])
        ->first();
        return response()->json(
           $user
        );
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
//     public function eexel()
//     {
//         $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
// $reader->setReadDataOnly(true);

//         $spreadsheet = $reader->load(public_path()."\app\public\student.xlsx" );
//     $spreadsheet->getActiveSheet();
//     //    $spreadsheet->getRibbonXMLData ();
//         return  $spreadsheet->getRibbonXMLData ('A');
//         // $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
// // $reader->setReadDataOnly(true);
// // $spreadsheet = $reader->load("05featuredemo.xlsx");
//     }

}