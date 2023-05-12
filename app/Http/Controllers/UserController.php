<?php

namespace App\Http\Controllers;

use App\Jobs\ActiveUserJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpParser\Node\Stmt\Foreach_;
use stdClass;
use App\Http\Controllers\Controller;
use App\Models\Colloge;
use App\Models\Post;
use App\Notifications\CreatePost;
// ------
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function dataDate(Request $request){
        $date =date("Y/m/d");
        return base_path();
       
        // $request->dataDate;
     }
    public function getAllUsers(){
        return User::all();
    }

    // public function getUserPosts 
     public function getUserPosts(){
        // $userPosts = Colloge::where('id',Auth('api')->user()->colloge_id)
       $userId= Auth('api')->user()->id;
        $userPosts = Post::where('user_id', $userId)
        ->with(['colloge'=> function ($colloge){
            $colloge->select('id','name');
           }])
           ->with(['section'=> function ($section){
            $section->select('id','name');
           }])
           ->with(['user'=> function ($user){
            $user->select('id','name','img');
           }])
           ->withCount('comment')
           ->withCount('like')
           ->latest()->take(50)
        ->get();
        $posts=[];
        foreach ($userPosts as   $post) {
           # code...
          $amILike = Post::where('id',$post->id)
          ->with(['like'=>function ($like){
            $like->where('user_id', Auth('api')->user()->id);
          }])
          ->first();
        //   $post->numberComments=$numberComments;
        //   $post->numberLikes=$amILike;
          if((int)$amILike[0]>0){
            $post->amILike= 1;
          }
          else{
             $post->amILike= 0;
          }
         

        //   if(isset($post->url)){
        //    str_replace("http://10.0.2.2","https://07f4-188-209-253-128.ngrok-free.app",$post->url);
        //    }
         array_push($posts,  $post );
        }
       return response()->json([
            'status'=>'success',
            'message' => 'The posts',
            'posts'=>$posts,
            ]);
        // return $userPosts;
    }
public function getUserPostsById(Request $request){

    // $userId= Auth('api')->user()->id;
    $userPosts = Post::where('user_id', $request->user_id)
    ->with(['colloge'=> function ($colloge){
        $colloge->select('id','name');
       }])
       ->with(['section'=> function ($section){
        $section->select('id','name');
       }])
       ->with(['user'=> function ($user){
        $user->select('id','name','img');
       }])
       ->withCount('comment')
       ->withCount('like')
       ->latest()->take(50)
    ->get();
    $posts=[];
    foreach ($userPosts as   $post) {
       # code...
      $amILike = Post::where('id',$post->id)
      ->with(['like'=>function ($like){
        $like->where('user_id', Auth('api')->user()->id);
      }])
      ->first();
    //   $post->numberComments=$numberComments;
    //   $post->numberLikes=$amILike;
      if((int)$amILike[0]>0){
        $post->amILike= 1;
      }
      else{
         $post->amILike= 0;
      }
     

    //   if(isset($post->url)){
    //    str_replace("http://10.0.2.2","https://07f4-188-209-253-128.ngrok-free.app",$post->url);
    //    }
     array_push($posts,  $post );
    }
   return response()->json([
        'status'=>'success',
        'message' => 'The posts',
        'posts'=>$posts,
        ]);

}
    public function getUserById(Request $request){
        $user = $this->getUser($request);

        return response()->json([
            'user' => $user ,
        ]);
    }
    private function getUser($request){
        return User::where('id', $request->user_id)
       ->with(['colloge'=> function ($colloge){
           $colloge->select('id','name');
       }])
       ->with(['section'=> function ($section){
           $section->select('id','name');
       }])
       ->first();
   }
    public function index(Request $request)
    {
        // return  response()->json(User::where('status',1)->count());
        // $users_handle = User::where('status',0)->get();
        // // return $users_handle;
        // foreach ($users_handle as $user){
        //     $user->update(['status' =>1]);
        // }
            ActiveUserJob::dispatch();
        
        return 'job successfully';
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCurrentUser()
    {
    //   $user=  User::with('post', function($post){
    //     return  $post->with('comment');
    //   })->where('id',Auth('api')->user()->id)->first();
    
    $user=  User::with('post', function($post){
        //    $post->find(1)->comment ;
      })->where('id',Auth('api')->user()->id)->first();
        return response()->json([
            'status'=>'success',
            'message'=>'the user ',
            'user'=>$user,
        
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         Storage::disk('myPath')->put('riad.txt', 'Hello Riyad');
        return 'store';
        
    }

 
    public function profileImageEdit(Request $request)
    {  
        $validator = $this->validate($request, [
  
            'file' => 'required|mimes:jpg,png,doc,docx,pdf,xls,xlsx,zip,m4v,avi,flv,mp4,mov',
  
        ]);
  
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();
        if ($save->isFinished()) {
            $response =  $this->saveFile($save->getFile());
  
            File::deleteDirectory(storage_path('app/chunks/user_profile/'));
  
            //your data insert code
            // $link = str_split()
           User::where('id',$request->user_id)->update([
                'img'=> url($response['link']) ,
                
            ]);

// $users = User::get();
// $user_cteate=Auth('api')->user()->name;

// Notification::send($users,new CreatePost($user_cteate,$post->id));

            //
  
            return response()->json([
                'status' => 'success',
                'link' => url($response['link']),
                'mime_type' => url($response['mime_type']),
                'extension' => url($response['extension']),
                'message' => 'File successfully uploaded.'
            ]);
        }
        $handler = $save->handler();
    }

//  /**
//  * Saves the file
//  *
//  * @param UploadedFile $file
//  *
//  * @return \Illuminate\Http\JsonResponse
//  */
protected function saveFile(UploadedFile $file)
{   $extension = $file->getClientOriginalExtension();
    $fileName = $this->createFilename($file);
    $mime = str_replace('/', '-', $file->getMimeType());
    $filePath = "public/uploads/chunk_uploads/user_profile/";
    $file->move(base_path($filePath), $fileName);
    $filePath = "uploads/chunk_uploads/user_profile/";
    return [
        'link' => $filePath . $fileName,
        'mime_type' => $mime,
        'extension' => $extension,
    ];
}
// /**
//  * Create unique filename for uploaded file
//  * @param UploadedFile $file
//  * @return string
//  */
protected function createFilename(UploadedFile $file)
{
    $extension = $file->getClientOriginalExtension();
    // $extension = $file->getClientOriginalName();
    $filename =  rand() . time() . "." . $extension;
    return $filename;
}
}