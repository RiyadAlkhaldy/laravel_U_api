<?php

namespace App\Http\Controllers;

use App\Models\TeacherTemp;
use App\Models\User;
use App\Traits\AuthVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherTempController extends Controller
{
    use AuthVerify;
    public function createTeacherTemp(Request $request){
       $checked= $this->checkIfTecherRegsterBefore($request);
       if(isset($checked)){
        return $checked;
       }
       $teacher = TeacherTemp::create([
        'name' => $request->name,
        'email' => $request->email,
        'colloge_id' => $request->colloge_id,
        'id_number' => $request->id_number ,
        'password' => Hash::make($request->password),
        'type'=>$request->type,
    ]);
    return response()->json([
        'status'=>'success',
        'user'=> $teacher->get(),
        ]);
    }
    public function agreeToAddTeacherToUser(Request $request){

        // $checked= $this->checkIfAdminOrTecherRegsterBefore($request);
        // if(isset($checked)){
        //  return $checked;
 
        // }

        try {
            $teacher = TeacherTemp::find($request->id) ;
            // return $teacher;
            // $teacher->id = null;
           unset( $teacher->id) ;
           unset( $teacher->created_at) ;
            unset($teacher->updated_at);

            $user = User::create($teacher->toArray());
            if(isset($user)){
                 $this->delete($request);
                 return response()->json([
             'status'=>'success',
             'teacher'=> $user,
             ]);
            }
           
        
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status'=>'error',
                'message'=> 'user are created before or duplicated',
                ]);
        }
      
     }
    public function getAllTeacherTemp(Request $request){
         $usersTemp = TeacherTemp::where('colloge_id',$request->colloge_id)->get();
           return response()->json([
            'status'=>'success',
            'teacher'=> $usersTemp,
            ]);
        }


        public function getOnlyTrashed(Request $request){
            //    return TeacherTemp::onlyTrashed()->get();
            // TeacherTemp::withTrashed()
            //     ->where('id', 1)
            //     ->restore();
               return TeacherTemp::onlyTrashed()->get();
                  
            }
    public function delete(Request $request){
       return TeacherTemp::destroy($request->id);
   
          
    }
}
