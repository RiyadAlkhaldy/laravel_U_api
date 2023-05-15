<?php

namespace App\Http\Controllers;

use App\Models\Colloge;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class CollogeController extends Controller
{
    public function getCollogePosts(Request $request){
        $data = post::where('colloge_id',$request->colloge_id)
    //    join('colloges','colloges.id','=','posts.colloge_id')
    //    ->
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
    // ->simplePaginate(20);
    $posts=[];
    foreach ($data as   $post) {
        # code...
       $amILike = Like::where('post_id',$post->id)->where('user_id',auth('api')->user()->id)
       ->with(['user'=>function ($like){
         $like->where('id', Auth('api')->user()->id);
       }])
       ->first();
     //   $post->numberComments=$numberComments;
     //   $post->numberLikes=$amILike;
     //   if((int)$amILike[0]>0){
         if( isset($amILike )  ){
         $post->amILike=1 ;
         // $post->amILike=$amILike ;
       }
       else{
          $post->amILike=  0;
         //  $post->amILike=  $amILike;
       }
         array_push($posts,  $post );
         if(isset($post->url)){
            str_replace("http://10.0.2.2:8000","https://ccd4-176-123-18-166.ngrok-free.app",$post->url);
            str_replace("http://127.0.0.1:8000","https://ccd4-176-123-18-166.ngrok-free.app",$post->url);
            }
        }
    
        return response()->json([
            'status'=>'success',
            'message' => 'The posts',
            'posts'=>$data,]);
    }
 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCollogePosts2(Request $request){
        $data = Colloge::
        join('sections','sections.colloge_id','=','colloges.id')
       -> join('posts','posts.section_id','=','sections.id')
         ->join('users','users.id','=','posts.id')
         ->where('posts.colloge_id',$request->colloge_id)
                    ->select(['posts.*','colloges.name as colloge_name','sections.name as section_name', 'users.name','users.img' ])
                                ->get();
                       $posts=[];
     foreach ($data as   $post) {
        # code...
       $numberComments = Post::find($post->id)->comments->count();
       $numberLikes = Post::find($post->id)->likes->count();
       $amILike = Post::find( 38)->likes->count();
       $post->numberComments=$numberComments;
       $post->numberLikes=$numberLikes;
       $post->amILike=$amILike;
      array_push($posts,  $post );
     }
        return response()->json([
            'status'=>'success',
            'message' => 'The posts',
            'posts'=>$posts,]);
    }

    public function index(Request $request)
    {
       $sections = Colloge::find(2);
       return $sections->sections;
    }


    /*
    function getAllCollge
    */
    public function getAllCollge()
    {
        
       $colloge = Colloge::get();
       return response()->json([
        'status'=>'success',
            'message' => 'The posts',
             'colloge' => $colloge]);
    //    $colloge = Colloge::with('sections')->get();
    //    return response()->json( $colloge);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Colloge  $colloge
     * @return \Illuminate\Http\Response
     */
    public function show(Colloge $colloge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Colloge  $colloge
     * @return \Illuminate\Http\Response
     */
    public function edit(Colloge $colloge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Colloge  $colloge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Colloge $colloge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Colloge  $colloge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Colloge $colloge)
    {
        //
    }
}
