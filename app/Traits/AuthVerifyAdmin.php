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
 

trait AuthVerifyAdmin {
  public function checkIfAdminRegsterBefore (Request $request){
    if(!isset($request->email,$request->id_number,$request->name ,$request->password)){
      return  response()->json([
        'status' => 'error',
        'message' => 'أملاء بقية الحقول',
    ], 200);
    }
    if(isset($request->id_number)){
      $admin = User:: where('id_number',$request->id_number)->first();
      if(isset($user)){
        return  response()->json([
          'status' => 'error',
          'message' => ' المستخدم لديه حساب من قبل',
      ], 200);
      }
    }
    $admin = User::where('email',$request->email)->first();
    if(isset($admin)){
      return  response()->json([
        'status' => 'error',
        'message' => '  الإيميل مستخدم من قبل',
    ], 200);
    }
    }
  
    public function registerVerifyAdmin(Request $request ){
       $student = DB::table('admins')->where('id_number',$request->id_number)->first();
        return $student;
    }
   
  //    public function getColloge($query){
  //     return Colloge::where('name',$query->colloge)->first();
  //   }
  //   public function setColloge($query){
  //         Colloge::create(['name'=>$query->colloge]);
  //     }
  //       public function getSection($query){
  //     return Section::where('name',$query->section)->first();

  //       }
  // public function setSection($query,$id){
  //   Section::create(['name'=>$query->section,'colloge_id'=>$id]);

  //       }
}