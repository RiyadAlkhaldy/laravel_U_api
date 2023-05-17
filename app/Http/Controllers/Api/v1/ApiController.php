<?php

namespace App\Http\Controllers\Api\v1;
 
// ------------
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CreatePost;
use Illuminate\Http\Request;
// ------
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Storage; 

class ApiController extends Controller
{
    public function file_upload(Request $request)
    {   
        // $validator = \Validator::make($request->all(), [
  
        //     'file' => 'required|mimes:jpg,png,doc,docx,pdf,xls,xlsx,zip,m4v,avi,flv,mp4,mov',
  
        // ]);
        $validator = $this->validate($request, [
  
            'file' => 'required|mimes:jpg,png,doc,docx,pdf,xls,xlsx,zip,m4v,avi,flv,mp4,mov',
  
        ]);
  
        // if ($validator->fails()) {
        //     return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        // }
        // return $request['id'];
        
  
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();
        if ($save->isFinished()) {
            $response =  $this->saveFile($save->getFile());
  
            File::deleteDirectory(storage_path('app/chunks/'));
  
    $post = $this->createPost($request,$response);
            // Post::create([]);

// $users = User::where('id','!=',Auth('api')->user()->id)->get();
// $users = User::get();
// $users = User::where('id','!=',Auth('api')->user()->id)->where('colloge_id',$request->colloge_id)->get();
// $user_cteate=Auth('api')->user()->name;
// Notification::send($users,new CreatePost($user_cteate,$post->id));
            //
            return response()->json([
                'status' => 'success',
                'link' => $response['link'],
                // 'link' => url($response['link']),
                'mime_type' => url($response['mime_type']),
                'extension' => url($response['extension']),
                'message' => 'File successfully uploaded.'
            ]);
        }
        $handler = $save->handler();
    }
    private function createPost(Request $request,$response){
      if(isset($request->section_id,$request->colloge_id)){
        return Post::create([
      'content'=>$request->content,
      'type'=>$request->type,
      'url'=> $response['link'] ,
      // 'url'=> url($response['link']) ,
      'user_id'=>  auth('api')->user()->id,
      'section_id'=> $request->section_id,
      'colloge_id'=>  $request->colloge_id,
      
  ]);
  }
        if(isset($request->section_id)){
             return Post::create([
           'content'=>$request->content,
           'type'=>$request->type,
           'url'=> $response['link'] ,
          //  'url'=> url($response['link']) ,
           'user_id'=> auth('api')->user()->id,
           'section_id'=> $request->section_id,
           'colloge_id'=>  $request->colloge_id,
           
       ]);
       }
       else{
           return Post::create([
               'content'=>$request->content,
               'type'=>$request->type,
               'url'=> $response['link'] ,
              //  'url'=> url($response['link']) ,
               'user_id'=>  auth('api')->user()->id,
               'colloge_id'=>  $request->colloge_id,
               
           ]); 
       }
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
    $date =date("Y/m/d");
    $filePath = "public/uploads/chunk_uploads/$date/";
    $file->move(base_path($filePath), $fileName);
    $filePath = "uploads/chunk_uploads/$date/";

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
 

 class UploaderController extends Controller
{
 /**
  * Create a new controller instance.
  *
  * @return void
  */
 public function __construct()
 {
     $this->middleware(['auth', 'verified']);
 }

 /**
  * Handles the file upload
  *
  * @param Request $request
  *
  * @return JsonResponse
  *
  * @throws UploadMissingFileException
  * @throws UploadFailedException
  */
 public function upload(Request $request) {  //from web route
   // create the file receiver
   $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

   // check if the upload is success, throw exception or return response you need
   if ($receiver->isUploaded() === false) {
     throw new UploadMissingFileException();
   }

   // receive the file
   $save = $receiver->receive();

   // check if the upload has finished (in chunk mode it will send smaller files)
   if ($save->isFinished()) {
     // save the file and return any response you need, current example uses `move` function. If you are
     // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
     return $this->saveFile($save->getFile(), $request);
   }

   // we are in chunk mode, lets send the current progress
   /** @var AbstractHandler $handler */
   $handler = $save->handler();

   return response()->json([
     "done" => $handler->getPercentageDone(),
     'status' => true
   ]);
 }

 /**
  * Saves the file
  *
  * @param UploadedFile $file
  *
  * @return JsonResponse
  */
  protected function saveFile(UploadedFile $file, Request $request) {
    $user_obj = auth()->user();
    $fileName = $this->createFilename($file);

    // Get file mime type
    $mime_original = $file->getMimeType();
    $mime = str_replace('/', '-', $mime_original);

    $folderDATE = $request->dataDATE;

    $folder  = $folderDATE;
    $filePath = "public/upload/medialibrary/{$user_obj->id}/{$folder}/";
    $finalPath = storage_path("app/".$filePath);

    $fileSize = $file->getSize();
    // move the file name
    $file->move($finalPath, $fileName);

    $url_base = 'storage/upload/medialibrary/'.$user_obj->id."/{$folderDATE}/".$fileName;

    return response()->json([
     'path' => $filePath,
     'name' => $fileName,
     'mime_type' => $mime
    ]);
 }

 /**
  * Create unique filename for uploaded file
  * @param UploadedFile $file
  * @return string
  */
  protected function createFilename(UploadedFile $file) {
    $extension = $file->getClientOriginalExtension();
    $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension

    //delete timestamp from file name
    $temp_arr = explode('_', $filename);
    if ( isset($temp_arr[0]) ) unset($temp_arr[0]);
    $filename = implode('_', $temp_arr);

    //here you can manipulate with file name e.g. HASHED
    return $filename.".".$extension;
  }

 /**
  * Delete uploaded file WEB ROUTE
  * @param Request request
  * @return JsonResponse
  */
  public function delete (Request $request){

    $user_obj = auth()->user();

    $file = $request->filename;

    //delete timestamp from filename
    $temp_arr = explode('_', $file);
    if ( isset($temp_arr[0]) ) unset($temp_arr[0]);
    $file = implode('_', $temp_arr);

    $dir = $request->date;

    $filePath = "public/upload/medialibrary/{$user_obj->id}/{$dir}/";
    $finalPath = storage_path("app/".$filePath);

    if ( unlink($finalPath.$file) ){
      return response()->json([
        'status' => 'ok'
      ], 200);
    }
    else{
      return response()->json([
        'status' => 'error'
      ], 403);
    }
  }

}

