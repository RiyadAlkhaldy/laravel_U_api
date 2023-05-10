<?php
namespace App\Traits;
use Illuminate\Http\Request;
 

trait UploadFile {
    public function uploadImage(Request $request,$folderName){
        $imageName = $request->file('photo')->getClientOriginalName();
        // $path = $request->file('photo')->store('images','public'); 
        $path = $request->file('photo')->storeAs($folderName,$imageName,'public'); 
        return $path;
    }
}