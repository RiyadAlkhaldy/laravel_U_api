<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function addLike(Request $request){
        $userId=Auth('api')->user()->id;
        $data = Like::where('user_id', $userId)->where('post_id',$request->post_id)->first();
    // return response()->json([$data]);

if(!isset($data)){
    $like = Like::create([
        'post_id'=> $request->post_id,
        'user_id'=> $userId,
        
        ]);
        return response()->json(['status'=> 'success',
                                'message'=> 'The like is done .',
                                'like'=> $like ]);
        return response()->json([$like]);
        
}
    
return response()->json([ 
    'status'=>'success',
    'message'=> 'comment add before',
]);
     
}

public function unLike(Request $request){
    $userId=Auth('api')->user()->id;
    $data = Like::where('user_id', $userId)->where('post_id',$request->post_id)->first();
// return response()->json([$data]);

if(isset($data)){
$like = Like::where('post_id', $request->post_id)
            ->where('user_id', $userId)->delete();
            return response()->json([$like]);
    }

return response()->json([ 
'status'=>'success',
'message'=> 'comment add before',
]);
 
}
}
