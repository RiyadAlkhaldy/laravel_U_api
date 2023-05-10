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
$users = User::get();
// $users = User::where('id','!=',Auth('api')->user()->id)->where('colloge_id',$request->colloge_id)->get();
$user_cteate=Auth('api')->user()->name;
Notification::send($users,new CreatePost($user_cteate,$post->id));
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
    private function createPost(Request $request,$response){
        if(isset($request->section_id)){
             return Post::create([
           'content'=>$request->content,
           'type'=>$request->type,
           'url'=> url($response['link']) ,
           'user_id'=>  $request->user_id,
           'section_id'=> $request->section_id,
           'colloge_id'=>  $request->colloge_id,
           
       ]);
       }
       else{
           return Post::create([
               'content'=>$request->content,
               'type'=>$request->type,
               'url'=> url($response['link']) ,
               'user_id'=>  $request->user_id,
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
    $filePath = "public/uploads/chunk_uploads/";
    $file->move(base_path($filePath), $fileName);
    $filePath = "uploads/chunk_uploads/";

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
