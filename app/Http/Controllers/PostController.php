<?php

namespace App\Http\Controllers;

use App\Events\RealtimePosts;
use App\Models\Colloge;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CreatePost;
// use Auth;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    use UploadFile;
    public function __construct(){
        // $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllPostsPage(Request $request){
        $data = post::
           with(['colloge'=> function ($colloge){
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
           ->latest()
        // ->get();
        ->paginate(20);
        // ->simplePaginate(20);
        $posts=[];
            foreach ($data as   $post) {
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
             array_push($posts,  $post );
        //    if(isset($post->url)){
        //     str_replace("http://10.0.2.2","https://07f4-188-209-253-128.ngrok-free.app",$post->url);
            }
        
            return response()->json([
                'status'=>'success',
                'message' => 'The posts',
                'posts'=>$data,]);
    }
    public function getAllPosts(){
        $data = Colloge::
        join('sections','sections.colloge_id','=','colloges.id')
       -> join('posts','posts.section_id','=','sections.id')
         ->join('users','users.id','=','posts.id')
                    //   ->
                        
                    // join('sections','sections.colloge_id','=','colloges.id')
                    //   ->join('posts','posts.section_id','=','sections.id')
                    //      ->join('users','users.section_id','=','sections.id')
                         
                    //    ->where( 'posts.user_id','=','users.id')
                    ->select(['posts.*','colloges.name as colloge_name','sections.name as section_name', 'users.name','users.img' ])

                        //  ->limit(9)     
                                // ->orderBy('posts.created_at', 'DESC')->take(10)
                                // ->latest()->take(10)
                                ->latest()->take(10)
                                ->get();
                    //    ->get(['posts.*','colloges.name as colloge_name','sections.name as section_name', 'users.name','users.img' ]);
                       $posts=[];
     foreach ($data as   $post) {
        # code...
       $numberComments = Post::find($post->id)->comment->count();
       $numberLikes = Post::find($post->id)->like->count();
       $amILike = Post::find($post->id)->like->count();
       $post->numberComments=$numberComments;
       $post->numberLikes=$numberLikes;
       $post->amILike=$amILike;
    //    if(isset($post->url)){
    //     str_replace("http://10.0.2.2","https://07f4-188-209-253-128.ngrok-free.app",$post->url);
    //     }
      array_push($posts,  $post );
     }
    //  https://4f81-178-130-104-122.ngrok-free.app -> http://127.0.0.1:8000
    //  https://9b63-175-110-9-28.ngrok-free.app
 
        return response()->json([
            'status'=>'success',
            'message' => 'The posts',
            'posts'=>$posts,]);
    }

    public function getAllPosts2(){
        $data = post::
    //    join('colloges','colloges.id','=','posts.colloge_id')
    //    ->
       with(['colloge'=> function ($colloge){
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

    // ->simplePaginate(20);
    $posts=[];
        foreach ($data as   $post) {
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
    //      if(isset($post->user->img)){
    //         str_replace("http://10.0.2.2:8000","https://ccd4-176-123-18-166.ngrok-free.app",$post->user->img);
    //         str_replace("http://127.0.0.1:8000","https://ccd4-176-123-18-166.ngrok-free.app",$post->user->img);
    //         }
    //    if(isset($post->url)){
    //     str_replace("http://127.0.0.1:8000","https://ccd4-176-123-18-166.ngrok-free.app",$post->url);

    //     str_replace("http://10.0.2.2:8000","https://ccd4-176-123-18-166.ngrok-free.app",$post->url);
        array_push($posts,  $post );

        // }
    }
        // https://ccd4-176-123-18-166.ngrok-free.app
        return response()->json([
            'status'=>'success',
            'message' => 'The posts',
            'posts'=>$posts,]);
    }

    public function getNumberCommentsLikes(Request $request){
        $data =  Post::find( $request->post_id) ;
        if(isset($data)){
            return response()->json([
                'status'=>'success',
                'numberComments'=>$data->comments->count(),
                'numberLikes'=>$data->likes->count(),
                ]);
        }
       
            return response()->json([
                'status'=>'success',
                'numberComments'=> 'null',]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $post = $this->createPost($request);
        // $users = User::where('id','!=',Auth('api')->user()->id)->get();
        $users = User::get();
        // $users = User::where('id','!=',Auth('api')->user()->id)->where('colloge_id',$request->colloge_id)->get();
        // $user=Auth('api')->user();
    
        
        // $this->sendNotificationsToOthers(  $post,$user);


        // Notification::send($users,new CreatePost($user_create,$post->id));
        // event(new RealtimePosts(['user_create'=>$user_create,'post_id'=> 'riad' ])) ;
        // broadcast(new RealtimePosts(['user_create'=>$user_create,'post_id'=>$post->id]))->toOthers();
        return   response()->json([
            'status' => 'success',
            'message' => 'Post  created and stored successfully',
            
        ]);
        
    }
    private function createPost(Request $request){
        if(isset($request->section_id)){
             return Post::create([
           'content'=>$request->content,
           'type'=>$request->type,
           'user_id'=>  $request->user_id,
           'section_id'=> $request->section_id,
           'colloge_id'=>  $request->colloge_id,
           
       ]);
       }
       else{
           return Post::create([
               'content'=>$request->content,
               'type'=>$request->type,
               'user_id'=>  $request->user_id,
               'colloge_id'=>  $request->colloge_id,
               
           ]); 
       }
    }
    private function sendNotificationsToOthers(Post $post,$user){
        // broadcast(new RealtimePosts( $post, $user))->toOthers();
        broadcast(new RealtimePosts( $post, $user));
        // event(new RealtimePosts( $post, $user));

    }
    // public function store(Request $request)
    // {
    //     $post = Post::create([
    //         'title'=>$request->title,
    //         'body'=>$request->body ,
    //     ]);
    //     $users = User::where('id','!=',Auth('api')->user()->id)->get();
    //     $user_cteate=Auth('api')->user()->name;

    //     Notification::send($users,new CreatePost($user_cteate,$post->id));
    //     return  $user_cteate;
        
    // }
    public function showNotifications(Post $post)
    
    {
        // $notifications = Auth('api')->user()->unreadNotifications;
        $notifications = Auth('api')->user()->notifications;
        return $notifications;
        
    }
    public function storeFile(Request $request)
    {
        $path = $this->uploadImage($request,'users');//like user image
       
        return $path; 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return  phpinfo();
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return 'edit';
        
    }
 
    public function update(Request $request, Post $post)
    {
        return 'update';
        
    }

    
    public function destroy(Request $request)
    {
        // Post::
        return response()->json(['status'=>'delete']);
    }
    public function delete(Request $request)
    {
        $post = Post::where('id',$request->post_id)->delete();

        if(isset($post)){
            return response()->json([
                'status'=>'success',
                'message'=> 'the post delete',
            ]);

        }
    }
}