<?php
namespace App\Traits;

use App\Models\Colloge;
use App\Models\Section;
use App\Models\Student;
use App\Models\TeacherTemp;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 

trait AuthVerify {
  public function checkIfTecherRegsterBefore (Request $request){
    if(!isset($request->email,$request->id_number,$request->name,$request->type,$request->password)){
      return  response()->json([
        'status' => 'error',
        'message' => 'أملاء بقية الحقول',
       
    ], 200);
    }
    if(isset($request->id_number)){
      $user = TeacherTemp:: where('id_number',$request->id_number)->first();
      if(isset($user)){
        return  response()->json([
          'status' => 'error',
          'message' => ' الرقم الضخصي مستخدم من قبل',
         
      ], 200);
      }
      $user = User:: where('id_number',$request->id_number)->first();
      if(isset($user)){
        return  response()->json([
          'status' => 'error',
          'message' => 'الرقم الضخصي مستخدم من قبل',
         
      ], 200);
      }
    }
    

     
      $user = TeacherTemp::where('email',$request->email)->first();
      if(isset($user)){
        return  response()->json([
          'status' => 'error',
          'message' => '  الإيميل مستخدم من قبل',
      ], 200);
      }

    }
    
  public function checkIfAdminOrTecherRegsterBefore (Request $request){
    if(!isset($request->email,$request->id_number,$request->name,$request->type,$request->password)){
      return  response()->json([
        'status' => 'error',
        'message' => 'أملاء بقية الحقول',
        // 'message' => 'the  id_number is used before',
        // 'user'=>$user,
        // 'user' => $user->find($user->id),
        // 'authorisation' => [
        //     'token' => null,
        //     'type' => 'bearer',
        // ]
    ], 200);
    }
    if(isset($request->id_number)){
    $user = User:: where('id_number',$request->id_number)->first();
    if(isset($user)){
      return  response()->json([
        'status' => 'error',
        'message' => ' الرقم الضخصي مستخدم من قبل',
       
    ], 200);
    }
  }
   if(isset($request->university_id)){
    $user = User::where('university_id',$request->university_id)->first();
    if(isset($user)){
      return  response()->json([
        'status' => 'error',
        // 'message' => 'the university_id  is used before',
        'message' => ' الرقم الجامعي مستخدم من قبل',
    ], 200);
    }
   }
    $user = User::where('email',$request->email)->first();
    if(isset($user)){
      return  response()->json([
        'status' => 'error',
        'message' => '  الإيميل مستخدم من قبل',
    ], 200);
    }
  }
  
  public function checkIfUserRegsterBefore (Request $request){
    if(!isset($request->email,$request->id_number,$request->university_id )){
      return  response()->json([
        'status' => 'error',
        'message' => 'أملاء بقية الحقول',
        // 'message' => 'the  id_number is used before',
        // 'user'=>$user,
        // 'user' => $user->find($user->id),
        // 'authorisation' => [
        //     'token' => null,
        //     'type' => 'bearer',
        // ]
    ], 200);
    }
    if(isset($request->id_number)){
    $user = User:: where('id_number',$request->id_number)->first();
    if(isset($user)){
      return  response()->json([
        'status' => 'error',
        'message' => ' الرقم الضخصي مستخدم من قبل',
       
    ], 200);
    }
  }
   if(isset($request->university_id)){
    $user = User::where('university_id',$request->university_id)->first();
    if(isset($user)){
      return  response()->json([
        'status' => 'error',
        // 'message' => 'the university_id  is used before',
        'message' => ' الرقم الجامعي مستخدم من قبل',
    ], 200);
    }
   }
    $user = User::where('email',$request->email)->first();
    if(isset($user)){
      return  response()->json([
        'status' => 'error',
        'message' => '  الإيميل مستخدم من قبل',
    ], 200);
    }
  }
    public function registerVerify(Request $request ){
       $student = Student::where('university_id',$request->university_id)->where('id_number',$request->id_number)->first();
        return $student;
    }
    // public function addCollogeOrSection( $query ){
    //     DB::table('users')->insert([
    //         'email' => 'kayla@example.com',
    //         'votes' => 0
    //     ]);
    //      return "student";
    //  }
     public function getColloge($query){
      return Colloge::where('name',$query->colloge)->first();
    }
    public function setColloge($query){
          Colloge::create(['name'=>$query->colloge]);
      }
        public function getSection($query){
      return Section::where('name',$query->section)->first();

        }
        public function setSection($query,$id){
          Section::create(['name'=>$query->section,'colloge_id'=>$id]);
      
              }
}