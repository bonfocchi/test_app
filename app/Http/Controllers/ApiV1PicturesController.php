<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use App\Picture;

use Storage;
use App\Http\Requests;
use Exception;
use Image;
use Validator;
use URL;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiV1PicturesController extends ApiV1Controller
{

  /**
   * Retrieve all pictures
   *
   * @return json
   */
  public function retrieve_all(Request $request)
  {

    $data = array();
    $errors = array();
    $base_url = URL::to('/');

    $admin = JWTAuth::parseToken()->authenticate();
    $images = $admin->pictures()->get();
    //$images = Picture::all();

    if ( !$images->isEmpty() ){
      foreach($images as $image){
        $new_element["id"] = $image->id;
        $new_element["admin_id"] = $image->admin_id;
        $new_element["title"] = $image->title;
        $new_element["description"] = $image->description;
        $new_element["wh_ratio"] = $image->wh_ratio;
        $new_element["cached_file_name"] = $image->cached_file_name;
        $new_element["storage_file_name"] = $image->storage_file_name;
        $new_element["created_at"] = $image->created_at->toDateTimeString();
        $new_element["updated_at"] = $image->updated_at->toDateTimeString();
        // Where is the user session?!?
        // if(){
        //   $new_element["purchased"] = 0 or 1;
        // }

        if ($image->storage_file_name != null){
          $image_link = presigned_url($image->storage_file_name);
          if (strpos($image_link, "http") === 0){
            $new_element["download_url"] = $image_link;
          }else{
            $new_element["download_url"] = $base_url . $image_link;
          }
        }else{
          $new_element["download_url"] = '';
        }
        array_push ($data, $new_element);
      }
    }

    $extras = array();
    $extras["meta"] = array();
    $extras["meta"]["limit"] = 30;
    $extras["meta"]["offset"] = 0;
    $extras["meta"]["total"] = $images->count();


    $success = 1;
    $code = 200;

    return $this->build_reply($request, $success, $code, $data, $errors, $extras );
  }

  /**
   * Retrieve one picture
   *
   * @return json
   */
  public function retrieve(Request $request, $id)
  {

    $data = array();
    $errors = array();
    $base_url = URL::to('/');

    if ( Picture::where('id', $id)->exists() ){

      $image = Picture::find($id);

      $data["id"] = $image->id;
      $data["admin_id"] = $image->admin_id;
      $data["title"] = $image->title;
      $data["description"] = $image->description;
      $data["wh_ratio"] = $image->wh_ratio;
      $data["cached_file_name"] = $image->cached_file_name;
      $data["storage_file_name"] = $image->storage_file_name;
      $data["created_at"] = $image->created_at->toDateTimeString();
      $data["updated_at"] = $image->updated_at->toDateTimeString();
      // Where is the user session? Currently we only have the admin token.
      // if(){
      //   $new_element["purchased"] = 0 or 1;
      // }
      if ($image->storage_file_name != null){
        $image_link = presigned_url($image->storage_file_name);
        if (strpos($image_link, "http") === 0){
          $data["download_url"] = $image_link;
        }else{
          $data["download_url"] = $base_url . $image_link;
        }
      }else{
        $data["download_url"] = '';
      }

      $success = 1;
      $code = 200;

    }else{
      $errors["message"] = "The resource that matches ID:".$id." was not found.";
      $success = 0;
      $code = 404;

    }

    return $this->build_reply($request, $success, $code, $data, $errors );
  }


  /**
   * Create a Picture
   *
   * @return json
   */
  public function create(Request $request)
  {
    $data = array();
    $errors = array();
    if (  $request->has('title') &&
          (strlen($request->title) <= 64) &&
          $request->has('description')
       ){

      $picture = Picture::create([
          'title' => $request->title,
          'description' => $request->description
      ]);

      $success = 1;
      $code = 201;
      $data = ["id" => $picture->id];

    }else{
      //$data["exists"] = 0;
      $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
      $errors["code"] = 400002;
      $errors["validation"] = array();

      if (!$request->has('title')){
        $errors["validation"]['title']['key'] = 'required';
        $errors["validation"]['title']['message'] = 'The title field is required.';
      }else if( strlen($request->title) > 64 ){
        $errors["validation"]['title']['key'] = 'max:64';
        $errors["validation"]['title']['message'] = 'The name may not be greater than 64 characters.';
      }

      if (!$request->has('description')){
        $errors["validation"]['description']['key'] = 'required';
        $errors["validation"]['description']['message'] = 'The description field is required.';
      }

      $success = 0;
      $code = 400;
    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }

  /**
   * Update Picture
   *
   * @return json
   */
  public function update(Request $request, $id)
  {
    $data = array();
    $errors = array();


    if (  Picture::where('id', $id)->exists() &&
          ( ( $request->has('title') && (strlen($request->title) <= 64) ) ||
            $request->has('description')
          )
       ){

      $picture = Picture::find($id);

      if ( $request->has('title') ){
        $picture->title = $request->title;
      }

      if ( $request->has('description') ){
        $picture->description = $request->description;
      }

      $picture->save();

      $data = ["updated" => 1];
      $success = 1;
      $code = 201;
      $data = ["id" => $picture->id];

    }else{
      //$data = ["updated" => 0];

      if ( !Picture::where('id', $id)->exists() ){
        $errors = [ "message" => "The resource that matches ID:".$id." does not found.",
                    "code" => 403001
                  ];
        $code = 403;
      }else if($request->has('title') && strlen($request->title) > 64 ){
        $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
        $errors["code"] = 400002;
        $errors["validation"] = array();

        $errors["validation"]['title']['key'] = 'max:64';
        $errors["validation"]['title']['message'] = 'The name may not be greater than 64 characters.';
        $code = 400;
      }else if( !$request->has('title') && !$request->has('description') ){
        //$errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
        $errors["message"] = "The title or the description need to be provided to update successfully.";
        $code = 400;
      }

      $success = 0;

    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }

  /**
   * Delete Picture
   *
   * @return json
   */
  public function delete(Request $request, $id)
  {
    $data = array();
    $errors = array();
    if ( Picture::where('id', $id)->exists() ){

      $user =  Picture::find($id);
      $user->delete();

      $data["deleted"] = 1;
      $success = 1;
      $code = 200;

    }else{
      //$data["deleted"] = 0;
      $errors["message"] = "The resource that matches ID:".$id." does not found.";
      $errors["code"] = 403001;
      $success = 0;
      $code = 403;
    }
    return $this->build_reply($request, $success, $code, $data, $errors );
  }




  /**
   * Upload Image and associate with Picture object
   *
   * @return json
   */
  public function upload(Request $request, $id)
  {

    $data = array();
    $errors = array();

    /*
    $this->validate($request, [
         'file' => 'required|file|image|mimes:jpeg,bmp,png,gif'
     ]);
     */

    $validator = Validator::make($request->all(), [
      'file' => 'required|file|image|mimes:jpeg,bmp,png,gif'
    ]);

    if ( !Picture::where('id', $id)->exists() ){
      // does not exist

      $errors = [ "message" => "The resource that matches ID:".$id." does not found.",
                  "code" => 403001
                ];
      $success = 0;
      $code = 403;

      return $this->build_reply($request, $success, $code, $data, $errors );

    }else if (  !$request->hasFile('file') ||
                !$request->file('file')->isValid()
             ){
      // File not selected
      $errors["validation"] = array();
      $errors["validation"]['file']['key'] = 'required';
      $errors["validation"]['file']['message'] = 'The file is required.';
      $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
      $errors["code"] = 400002;

      $success = 0;
      $code = 400;

      return $this->build_reply($request, $success, $code, $data, $errors );

    }else if ( $validator->fails() ) {
      // File type not accepted
      $errors["validation"] = array();
      $errors["validation"]['file']['key'] = 'mimes:jpeg,jpg,png,bmp,gif';
      $errors["validation"]['file']['message'] = 'The file must be a file of type: jpeg, jpg, png, bmp, gif.';
      $errors["message"] = "The request parameters are incorrect, please make sure to follow the HiCat document.";
      $errors["code"] = 400002;

      $success = 0;
      $code = 400;

      return $this->build_reply($request, $success, $code, $data, $errors );

    }else{

      // All good

      $file = $request->file('file');
      $filename = $file->getClientOriginalName();

      $filename = md5($filename . microtime())."_img_". $filename;

      while( Storage::exists($filename) ){
        $filename_array = explode("_img_", $filename);
        $filename_array[0] = md5($filename . microtime());
        $filename = implode ( "_img_",  $filename_array );
      }

      $original_image = Image::make($file->getRealPath());
      $thumb_image = Image::make($file->getRealPath())->resize(null, 300, function ($constraint) {
           $constraint->aspectRatio();
           $constraint->upsize();
       });

      $original_image = $original_image->stream();
      $thumb_image = $thumb_image->stream();

      try{
        Storage::put($filename, $original_image->__toString());
        Storage::put('thumbnails/'.$filename, $thumb_image->__toString());
      }catch(Exception $e){
        $errors = [ "message" => "A fatal error has occurred while creating the files to storage, please try again.",
                    "code" => 500010
                  ];
        $success = 0;
        $code = 500;
      }


      $admin = JWTAuth::parseToken()->authenticate();

      $picture = new Picture;

      $picture->admin_id = $admin->id;
      $picture->storage_file_name = $filename;

      $picture->save();

      $success = 1;
      $code = 201;
      $data = [ "updated" => 1,
                "updated_id" => $picture->id
              ];

      return $this->build_reply($request, $success, $code, $data, $errors );


    }

  }

  /**
   * Download Picture
   *
   * @return json
   */
  public function download(Request $request, $id)
  {
    $data = array();
    $errors = array();
    if ( Picture::where('id', $id)->exists() ){

      $image =  Picture::find($id);
      $base_url = URL::to('/');

      if (  !$request->has('size') ||
            ($request->has('size') && ($request->size == 'full') )
         ){
           // Full size
           //dd($image->storage_file_name);
           $original_image = Image::make(file_get_contents( $base_url . presigned_url($image->storage_file_name) ) );
           $contents = $original_image->stream();

         }else if ($request->size == 'medium'){
           // Medium size
           $medium_image = Image::make(file_get_contents( $base_url . presigned_url($image->storage_file_name) ) )->resize(null, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
           $contents = $medium_image->stream();
         }else{
           // Thumbnail size
           $thumbnail_image = Image::make(file_get_contents( $base_url . presigned_url('thumbnails/'.$image->storage_file_name) ) );
           $contents = $thumbnail_image->stream();
         }


      $response = \Response::make($contents, 200);
      $response->header('Content-Type', 'application/image'); // To test on Postman use: image/*
      return $response;

    }else{
      //$data["deleted"] = 0;
      $errors["message"] = "The resource that matches ID:".$id." does not found.";
      $errors["code"] = 403001;
      $success = 0;
      $code = 403;
    }

    return $this->build_reply($request, $success, $code, $data, $errors );
  }




}
