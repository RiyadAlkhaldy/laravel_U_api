<?php

use App\Events\NotificationRecieved;
use App\Events\RealtimePosts;
use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollogeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TeacherTempController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

//  # app_id = "1589363"
// # key = "86a5ac360934412cdaa8"
// # secret = "87de7c64a1d5911cd231"
// # cluster = "ap2"

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//  Route::get('command','')


Broadcast::routes(['middleware'=>['auth:api']]);

// Broadcast::routes(['middleware' => ['auth:api']]);


Route::controller(TeacherTempController::class)->prefix('auth-temp')->group(function () {
Route::post('create-teacher-temp' ,'createTeacherTemp' ) ;
    Route::post('login', 'login');
    Route::post('delete', 'delete');
    Route::post('get-all-users-tmep', 'getAllTeacherTemp');
    Route::post('agree-for-teacher', 'agreeToAddTeacherToUser');
    
    

});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login');
    Route::post('register-admin', 'registerAdmin');
    Route::post('register-teacher', 'registerTeacher');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('create-teacher-temp', 'createTeacherTemp');
    Route::get('me', 'me');

});
Route::controller(UserController::class)->prefix('user')->group(function (){
    Route::get('get-all-users','getAllUsers');
    Route::get('get-current-user','getCurrentUser');
    Route::post('upload-profile-image','profileImageEdit');
    Route::post('get-user-posts','getUserPosts');
    Route::post('get-user-posts-by-userid','getUserPostsById');
    Route::post('get-user-by-id','getUserById');
    Route::get('date','dataDate');
   
    // getUserById
    //  getCurrentUser profileImage get-user-posts

});
Route::controller(CollogeController::class)->prefix('colloge')->group(function () {
    Route::get('index', 'index');
    Route::post('get-colloge-posts', 'getCollogePosts');
    Route::post('get-all-colloge', 'getAllCollge');

    

});
Route::controller(SectionController::class)->prefix('section')->group(function () {
    Route::get('index', 'index');
    Route::post('get-section-posts', 'getSectionPosts');


});
// Route::controller(PostController::class)->prefix('post')->group(function () {
//     Route::get('index', 'index');
    

// });
Route::controller(PostController::class)->prefix('posts') ->group(function (){
    Route::post('create', 'create');
    Route::post('storefile', 'storeFile');
    Route::post('edit', 'edit');
    Route::post('delete', 'delete');
    Route::get('show', 'show');
    Route::post('showNotifications', 'showNotifications');
    // Route::get('get-all-post', 'getAllPosts');

    Route::get('get-all-posts-page', 'getAllPostsPage');
    Route::post('get-all-posts', 'getAllPosts');
    Route::post('get-all-posts-by-userid', 'getAllPostsByUserId');
    Route::post('get-all-posts2', 'getAllPosts2');
    Route::post('get-number-comments-likes', 'getNumberCommentsLikes');
});
Route::controller(CommentController::class)->prefix('comment')->group(function () {
    Route::post('get-all-comments', 'getAllComments');
    Route::post('add-comment', 'addComment');
    Route::post('delete-comment', 'deleteComment');
    Route::post('get-number-comments', 'getNumberComments');
    

});
Route::controller(LikeController::class)->prefix('like')->group(function () {
    Route::post('add-like', 'addLike');
    Route::post('un-like', 'unLike');
//
    Route::post('get-all-comments', 'getAllComments');
    Route::post('get-number-comments', 'getNumberComments');
    

});

// Route::post('/v1/file_upload', 'App\Http\Controllers\Api\v1\ApiController@file_upload');

Route::controller(ApiController::class)->prefix('v1')->group(function (){
    
    Route::post( 'file_upload',  'file_upload');
    
});

// Route::get('/noty', function (Request $request) {
    
//    return   event(new RealtimePosts( 'request->msg'));
             
// });
// Route::get('/command', function ( ) {
//     $this->line('hello');
//     return   'hello';
              
//  });





// Route::controller(UserController::class)->prefix('user')->group(function () {
//     Route::post('', 'index');
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::post('logout', 'logout');
//     Route::post('refresh', 'refresh');
//     Route::get('me', 'me');

// });