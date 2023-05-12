<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;


class CommentController extends Controller

{
    public function __construct(){
        // $this->middleware('auth:api');
    }
    public function getAllComments(Request $request){
        $data = Post::join('comments','comments.post_id','=','posts.id')
                    ->join('users','users.id','=','comments.user_id')
        ->where('post_id',$request->post_id)->get(['comments.*','users.name','users.img','users.id as user_id']);
        return  response()->json([
            'status'=>'success',
            'comment'=> $data,]);
      
        }
    public function getAllComments2(Request $request){
    $data = Post::join('comments','comments.post_id','=','posts.id')
                ->join('users','users.id','=','comments.user_id')
    ->where('post_id',$request->post_id)->get(['comments.*','users.name','users.img']);
    return  response()->json([
        'status'=>'success',
        'comment'=> $data,]);
  
    }
    public function getNumberComments(Request $request){
        $data =  Post::find( $request->post_id)->comments()->count() ;
        if(isset($data)){
            return response()->json([
                'status'=>'success',
                'numberComments'=>$data,]);
        }
       
            return response()->json([
                'status'=>'success',
                'numberComments'=> 'null',]);
    }
    public function addComment(Request $request){
        $data = Comment::create([
        'post_id'=> $request->post_id,
        'user_id'=> $request->user_id,
        'comment'=> $request->comment,

        ]);
        return response()->json([
            'status'=>'success',
            'comment'=>  $data,
            ]);
        }
public function deleteComment(Request $request){
    $commentDelete = Comment::destroy($request->id);
return $commentDelete;
}
        /*
        create Comment 
        */
       public function create(Request $request){

       }
}
